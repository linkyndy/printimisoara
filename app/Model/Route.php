<?php
App::uses('AppModel', 'Model');

class Route extends AppModel {

	public $validate = array(
		'route_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid route ID!',
			),
		),
		'station_line_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid station line ID!',
			),
		),
		'station_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid station ID!',
			),
		),
		'line_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid line ID!',
			),
		),
		'order' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Invalid oreder!',
			),
		),
	);

	public $belongsTo = array(
		'StationLine' => array(
			'className' => 'StationLine',
			'foreignKey' => 'station_line_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Station' => array(
			'className' => 'Station',
			'foreignKey' => 'station_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Line' => array(
			'className' => 'Line',
			'foreignKey' => 'line_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);


	
	public $lineCombinations = array();//imported from Line
	public $linePoints = array();//imported from Line
	public $routesQueue = array();//list of pairs of StationLine's which should be linked with a route using the provided lines
	public $savedRoutes = array();//list of routes to be saved in the database
	public $stationLinesCoords = array();//list of lat/lng coordinates for each StationLine
	
	public $coords = array();
	private $__distance; //straight line
	public $radius = array();
	public $stations = array();
	public $routes = array();
	public $routeId;
	protected $_nodeStationLines = array();//station lines to visit in the next backtracking loop
	protected $_route;
	protected $_routeData;//associated route data; same data as $this->routes, but sorted for optimization before output
	protected $_routeInfo;
	protected $_routeList = array();//list of routes from database which match the current query
	
	public $stationLines = array();
	public $stationLine;
	protected $_stations = array();
	protected $_visitedStationGroups;//each visited stationGroup for each line
	protected $_returnToNodeId;
	public $time = array();//execution time for set keys
	protected $_time = array();//stores the start time of an action
	//private $__stationsByGroup = array();//stations that belong to [stationGroupId]; retrieve node stations faster - Now Cached
	//private $__nodeStationLinesByLine = array();//station lines in node [stationGroupId], with [lineId] records first - Now Cached
	private $__regions = array();//regions of from and to stations 
	protected $_minimumNumberOfStations = 100;//minimum number of stations which lead to destination; break routes which have +10 stations
	protected $_minimumNumberOfLines = 10;//minimum number of lines which lead to destination; break routes which have +1 lines
	//protected $_numberOfMetropolitan = 4;//number of metropolitan lines in routes; break routes which have more metro lines than this variable
	//protected $_numberOfExpress = 4;//number of express lines in routes; break routes which have more express lines than this variable
	
	public function compute(){
		$this->bindModel(array('belongsTo' => array('Station', 'StationLine', 'Line')));
		
		$this->_setTime('total');
		
		Cache::clear();
		
		$this->_setTime('validateNodes');
		if(
			!$this->Station->validateNodes($this->Station->find('list', array(
				'fields' => array('Station.station_group_id'),
				'group' => array('Station.station_group_id')
			)))
		){
			$this->_setTime('validateNodes');
			$this->_logNodeValidationFailed();
			return false;
		}
		$this->_setTime('validateNodes');
		
		$this->_setTime('computeFollowingStationLines');
		if(
			!$this->StationLine->FollowingStationLine->compute($this->Line->find('list', array(
				'fields' => array('Line.id')
			)))
		){
			$this->_setTime('computeFollowingStationLines');
			$this->_logComputeFollowingStationLinesFailed();
			return false;
		}
		$this->_setTime('computeFollowingStationLines');
		
		$this->_setTime('computeFalseStartEnds');
		if(!$this->StationLine->computeFalseStartEnd()){
			$this->_setTime('computeFalseStartEnds');
			$this->_logComputeFalseStartEndsFailed();
			return false;	
		}
		$this->_setTime('computeFalseStartEnds');
		
		$this->_setTime('computeRoutesQueue');
		if(!$this->computeRoutesQueue()){
			$this->_setTime('computeRoutesQueue');
			$this->_logComputeRoutesQueueFailed();
			return false;	
		}
		$this->_setTime('computeRoutesQueue');
		
		$this->_setTime('computeRoutes');
		if(!$this->computeRoutes()){
			$this->_setTime('computeRoutes');
			$this->_logComputeRoutesFailed();
			return false;	
		}
		$this->_setTime('computeRoutes');
		
		$this->_setTime('total');
		$this->_logComputeSuccess();
		return true;
	}
	
	public function computeRoutesQueue(){
		if(!$this->Line->lineCombinations()){
			return false;	
		} else {
			$this->lineCombinations = $this->Line->lineCombinations;	
		}
		
		if(!$this->Line->linePoints()){
			return false;	
		} else {
			$this->linePoints = $this->Line->linePoints;	
		}
		
		foreach($this->lineCombinations as $lineCombination){
			if(count($lineCombination) == 1){
				$lineId = $lineCombination;
				$this->routesQueue[] = array(
					'points' => $this->linePoints[$lineId][0],
					'lines' => array($lineId)
				);
				$this->routesQueue[] = array(
					'points' => $this->linePoints[$lineId][1],
					'lines' => array($lineId)
				);
			} elseif(count($lineCombination) == 2){
				$firstLineId = $lineCombination[0];
				$secondLineId = $lineCombination[1];
				for($i = 0; $i < 2; $i++){
					for($j = 0; $j < 2; $j++){
						$this->routesQueue[] = array(
							'points' => array($this->linePoints[$firstLineId][$i][0], $this->linePoints[$secondLineId][$j][1]),
							'lines' => $lineCombination
						);
						$this->routesQueue[] = array(
							'points' => array($this->linePoints[$secondLineId][$i][0], $this->linePoints[$firstLineId][$j][1]),
							'lines' => $lineCombination
						);	
					}
				}
			} elseif(count($lineCombination) == 3){
				$firstLineId = $lineCombination[0];
				$secondLineId = $lineCombination[1];
				$thirdLineId = $lineCombination[2];
				for($i = 0; $i < 2; $i++){
					for($j = 0; $j < 2; $j++){
						$this->routesQueue[] = array(
							'points' => array($this->linePoints[$firstLineId][$i][0], $this->linePoints[$secondLineId][$j][1]),
							'lines' => $lineCombination
						);
						$this->routesQueue[] = array(
							'points' => array($this->linePoints[$firstLineId][$i][0], $this->linePoints[$thirdLineId][$j][1]),
							'lines' => $lineCombination
						);
						$this->routesQueue[] = array(
							'points' => array($this->linePoints[$secondLineId][$i][0], $this->linePoints[$firstLineId][$j][1]),
							'lines' => $lineCombination
						);
						$this->routesQueue[] = array(
							'points' => array($this->linePoints[$secondLineId][$i][0], $this->linePoints[$thirdLineId][$j][1]),
							'lines' => $lineCombination
						);
						$this->routesQueue[] = array(
							'points' => array($this->linePoints[$thirdLineId][$i][0], $this->linePoints[$firstLineId][$j][1]),
							'lines' => $lineCombination
						);
						$this->routesQueue[] = array(
							'points' => array($this->linePoints[$thirdLineId][$i][0], $this->linePoints[$secondLineId][$j][1]),
							'lines' => $lineCombination
						);
					}
				}
			}
		}
		
		return true;
	}
	
	public function computeRoutes(){
		if(empty($this->routesQueue)){
			return false;	
		}
		
		if(!$this->stationLinesCoords = $this->StationLine->getCoordsForAll()){
			return false;	
		}
		
		$this->query('TRUNCATE '.$this->useTable);
		
		$this->routeId = 1;
		foreach($this->routesQueue as $i => $routeInQueue){
			$this->_stations = array(
				'from' => array($this->StationLine->field('station_id', array('StationLine.id' => $routeInQueue['points'][0]))),
				'to' => array($this->StationLine->field('station_id', array('StationLine.id' => $routeInQueue['points'][1])))
			);//used only for validating regions. has nothing to do with $this->stationLines in this computing method
			
			$this->coords = array(
				'from' => array(
					'lat' => $this->stationLinesCoords[$routeInQueue['points'][0]]['lat'],
					'lng' => $this->stationLinesCoords[$routeInQueue['points'][0]]['lng']
				),
				'to' => array(
					'lat' => $this->stationLinesCoords[$routeInQueue['points'][1]]['lat'],
					'lng' => $this->stationLinesCoords[$routeInQueue['points'][1]]['lng'] 
				)
			);
			
			$this->stationLines = array(
				'from' => $this->StationLine->find('all', array('conditions' => array('StationLine.id' => $routeInQueue['points'][0]))),
				'to' => $this->StationLine->find('list', array('fields' => array('StationLine.id'),	'conditions' => array('StationLine.id' => $routeInQueue['points'][1])))
			);
			
			$data = $this->discover(null, $routeInQueue['lines']);
			if(!$this->routeExists() && !empty($data) && !empty($data['routes'])){	
				foreach($data['routes'] as $route){
					$save = array();
					$order = 1;
					
					foreach($route['route'] as $routePoint){
						$save[] = array(
							'route_id' => $this->routeId,
							'station_line_id' => $routePoint['StationLine']['id'],
							'station_id' => $routePoint['Station']['id'],
							'line_id' => $routePoint['Line']['id'],
							'order' => $order++
						);	
					}
					
					if($this->saveMany($save)){				
						$this->routeId++;
					} else {
						return false;	
					}
				}
			}
		}
		
		return true;
	}
	
	public function routeExists(){
		$fromStationLine = $this->stationLines['from'][0]['StationLine']['id'];
		$toStationLine = array_values($this->stationLines['to']);
		$toStationLine = $toStationLine[0];
		
		$check = $this->find('count', array(
			'conditions' => array(
				'Route.station_line_id' => $fromStationLine,
				'Route2.station_line_id' => $toStationLine,
				'Route.order <= Route2.order'
			),
			'joins' => array(
				array(
					'table' => 'routes',
					'alias' => 'Route2',
					'type' => 'INNER',
					'conditions' => array(
						'Route.route_id = Route2.route_id'
					)
				)
			)
		));
		return ($check > 0);
	}
	
	public function fetch($coords = array()){//fetch routes based on the database cache
		$this->bindModel(array('belongsTo' => array('Station', 'StationLine', 'Line')));
		
		$this->_setTime('total');
		
		if(!$this->_validCoords($coords)){
			$this->_logInvalidCoords();
			return false;	
		}
		
		$this->_setTime('nearStations');
		if(!$this->__getNearStations()){
			$this->_setTime('nearStations');
			$this->_logStationsNotFound();
			return false;	
		}
		$this->_setTime('nearStations');
		
		$this->_setTime('stationLinesLookup');
		if(!$this->__buildStationLinesLookup()){
			$this->_setTime('stationLinesLookup');
			$this->_logStationLinesLookupNotFound();
			return false;	
		}
		$this->_setTime('stationLinesLookup');
		
		$this->_setTime('checkCachedRoutes');
		if(!$this->_checkCachedRoutes()){
			$this->_setTime('checkCachedRoutes');
			
			$this->_setTime('routeList');
			if(!$this->_getRouteList()){
				$this->_setTime('routeList');
				$this->_logRouteListNotFound();
				return false;
			}
			$this->_setTime('routeList');
			
			$this->_setTime('routeData');
			if(!$this->_getRouteData()){
				$this->_setTime('routeData');
				$this->_logRouteDataNotFound();
				return false;
			}
			$this->_setTime('routeData');
		} else {
			$this->_setTime('checkCachedRoutes');
			$this->_setTime('routeList');
			$this->_setTime('routeList');
			$this->_setTime('routeData');
			$this->_setTime('routeData');
		}
		
		$this->_setTime('total');
		
		return array(
			'coords' => $this->coords,
			'radius' => $this->radius,
			'stations' => $this->stations,
			'routes' => $this->routes,
			'time' => $this->time
		);
	}
	
	public function discover($coords = array(), $linesStack = array()){//get routes using backtracking
		$this->bindModel(array('belongsTo' => array('Station', 'StationLine', 'Line')));
		
		if(!$this->__init()){
			$this->_logFailedInit();
			return false;	
		}
		
		$this->_setTime('total');
		
		if(!empty($coords)){
			if(!$this->_validCoords($coords)){
				$this->_logInvalidCoords();
				return false;	
			}
			
			$this->_setTime('nearStations');
			if(!$this->__getNearStations()){
				$this->_setTime('nearStations');
				$this->_logStationsNotFound();
				return false;	
			}
			$this->_setTime('nearStations');
			
			$this->_setTime('stationLinesLookup');
			if(!$this->__buildStationLinesLookup()){
				$this->_setTime('stationLinesLookup');
				$this->_logStationLinesLookupNotFound();
				return false;	
			}
			$this->_setTime('stationLinesLookup');
		}
		
		$this->__distance = $this->__distanceBetween($this->coords['from'], $this->coords['to']);
		
		$this->_setTime('back');
		$this->__getRoutes(0, $linesStack, $this->stationLines['from'], 0);
		$this->_setTime('back');
		
		$this->_setTime('similarity');
		$this->_checkSimilarRoutes();
		$this->_setTime('similarity');
		
		$this->_setTime('optimization');
		$this->_optimizeRoutes();
		$this->_setTime('optimization');
		
		$this->_setTime('total');
		
		return array(
			'coords' => $this->coords,
			'radius' => $this->radius,
			'stations' => $this->stations,
			'routes' => $this->routes,
			'time' => $this->time
		);
	}
	
	private function _checkCachedRoutes(){
		$fromStationGroupId = $this->stations['from'][0]['Station']['station_group_id'];
		$toStationGroupId = $this->stations['to'][0]['Station']['station_group_id'];
		
		if(($this->routes = Cache::read('Routes.'.$fromStationGroupId.'_'.$toStationGroupId)) === false){
			$this->routes = array();
			return false;
		}
		
		return true;
	}
	
	private function _getRouteList(){
		$this->recursive = -1;
		$this->_routeList = $this->find('all', array(
			'fields' => array('Route.route_id', 'Route.order', 'Route2.order'),
			'conditions' => array(
				'Route.station_line_id' => Set::extract('/StationLine/id', $this->stationLines['from']),
				'Route2.station_line_id' => $this->stationLines['to'],
				'Route.order <= Route2.order'
			),
			'group' => 'Route.station_line_id, Route2.station_line_id, (Route2.order - Route.order)',
			'joins' => array(
				array(
					'table' => 'routes',
					'alias' => 'Route2',
					'type' => 'INNER',
					'conditions' => array(
						'Route.route_id = Route2.route_id'
					)
				)
			)
		));
		
		if(empty($this->_routeList)){
			return false;	
		}
		
		foreach($this->_routeList as &$route){
			$route = array(
				'route_id' => $route['Route']['route_id'],
				'from_order' => $route['Route']['order'],
				'to_order' => $route['Route2']['order']
			);
		}
		unset($route);
	
		return true;
	}
	
	private function _getRouteData(){
		$this->recursive = 1;
		foreach($this->_routeList as $route){
			$routeData = $this->find('all', array(
				'conditions' => array(
					'Route.route_id' => $route['route_id'],
					'Route.order >=' => $route['from_order'],
					'Route.order <=' => $route['to_order']
				)
			));
			$this->routes[] = array(
				'data' => array(
					'number_of_lines' => count(array_unique(Set::extract('/Line/id', $routeData))),
					'number_of_nodes' => '?',
					'number_of_stations' => count($routeData),
				),
				'route' => $routeData
			);
		}
		
		//optimize results
		$minimumNumberOfLines = min(Set::extract('/data/number_of_lines', $this->routes));		
		foreach($this->routes as $i => $route){
			if($route['data']['number_of_lines'] > $minimumNumberOfLines){
				unset($this->routes[$i]);	
			}
		}
		
		$minimumNumberOfStations = min(Set::extract('/data/number_of_stations', $this->routes));
		foreach($this->routes as $i => $route){
			if($route['data']['number_of_stations'] > $minimumNumberOfStations + 6){
				unset($this->routes[$i]);
			}
		}
		
		//cache results
		$fromStationGroupId = $this->stations['from'][0]['Station']['station_group_id'];
		$toStationGroupId = $this->stations['to'][0]['Station']['station_group_id'];
		Cache::write('Routes.'.$fromStationGroupId.'_'.$toStationGroupId, $this->routes);
		
		return true;
	}
	
	private function __init(){
		$this->routes = array();
		$this->routeId = 0;
		$this->_route = array();
		$this->_routeData = array();
		$this->_routeInfo = array();
		$this->_visitedStationGroups = array();
		$this->_returnToNodeId = false;
		$this->_minimumNumberOfStations = 100;
		$this->_minimumNumberOfLines = 10;
		return true;
	}
	
	private function __getNearStations(){		
		if(!$this->stations['from'] = $this->Station->nearStations($this->coords['from'])){
			return false;
		}
		$this->_stations['from'] = Set::extract('/Station/id', $this->stations['from']);
		$this->radius['from'] = $this->Station->radius;
		
		if(!$this->stations['to'] = $this->Station->nearStations($this->coords['to'])){
			return false;
		}
		$this->_stations['to'] = Set::extract('/Station/id', $this->stations['to']);
		$this->radius['to'] = $this->Station->radius;
		
		return true;
	}
	
	private function __buildStationLinesLookup(){
		if(
			!$this->stationLines['from'] = $this->StationLine->find('all', array(
				'conditions' => array('StationLine.station_id' => $this->_stations['from'])
			))
		){
			return false;
		}
		
		if(
			!$this->stationLines['to'] = $this->StationLine->find('list', array(
				'fields' => array('StationLine.id'), 
				'conditions' => array('StationLine.station_id' => $this->_stations['to'])
			))
		){
			return false;
		}
		
		return true;
	}
		
	private function __getRoutes($nodeId, $linesStack = array(), $stationLines, $fartherBy){
		foreach($stationLines as $stationLine){
			if(
				$this->_returnToNodeId !== false && 
				$this->_returnToNodeId < $nodeId
			){//if line arrived at destination, return from where you took that line
				break;	
			} else {
				$this->_returnToNodeId = false;	
			}
			
			$gettingFartherBy = $fartherBy;
			
			$this->_route[$nodeId] = array();
			$this->_visitedStationGroups[$nodeId] = array();
			
			$stationLine['Station']['distance'] = $this->__distanceBetween($this->coords['to'], array(
				'lat' => $stationLine['Station']['lat'], 
				'lng' => $stationLine['Station']['lng']
			));
				
			$this->_route[$nodeId][] = $stationLine;
			$this->_visitedStationGroups[$nodeId][] = $stationLine['Station']['station_group_id'];
			
			$this->_setTime('following');
			$followingStationLines = $this->StationLine->followingStationLines($stationLine['StationLine']['id']);
			$this->_setTime('following');
			
			foreach($followingStationLines as $followingStationLine){
				$this->stationLine = $followingStationLine;
				$this->stationLine['Station']['distance'] = $this->__distanceBetween($this->coords['to'], array(
					'lat' => $this->stationLine['Station']['lat'], 
					'lng' => $this->stationLine['Station']['lng']
				));
				
				$this->_route[$nodeId][] = $this->stationLine;
				$this->_visitedStationGroups[$nodeId][] = $this->stationLine['Station']['station_group_id'];
				
				if($this->stationLine['Station']['distance'] > $this->_route[$nodeId][count($this->_route[$nodeId]) - 2]['Station']['distance']){
					$gettingFartherBy++;	
				} else {
					$gettingFartherBy = 0;
				}
				
				if(!$this->_validLine($nodeId)){
					break;	
				}
				if(!$this->_validChanges($nodeId)){
					break;	
				}
				if(!$this->_validNumberOfStations($nodeId)){
					break;
				}
				if(in_array($this->stationLine['StationLine']['id'], $this->stationLines['to'])){
					$this->_saveRoute($nodeId);
					break;
				}
				if(!$this->_validRegion()){
					break;
				}
				if(!$this->_validRadius()){
					break;
				}
				if(!$this->_validDistance($gettingFartherBy)){
					break;	
				}
				if(!$this->_validStationGroup($nodeId)){
					break;	
				}
				if($this->_validNode($linesStack)){
					$this->__getRoutes($nodeId + 1, $linesStack, $this->_nodeStationLines, $gettingFartherBy);
					break;
				}
			}
		}
	}
		
	protected function _validLine($node_id){
		$this->_setTime('line');
		
		$slicedRoute = array_slice($this->_route, 0, $node_id + 1);
		
		$lines = array();
		foreach($slicedRoute as $id => $node){
			$line_id = $node[0]['Line']['id'];
			
			if(
				$id > 0 && 
				$line_id != $slicedRoute[$id - 1][0]['Line']['id'] && 
				in_array($line_id, $lines)
			){
				$this->_setTime('line');
				return false;	
			}
			
			if(!in_array($line_id, $lines)){
				$lines[] = $line_id;
			}
			
			if(count($lines) > $this->_minimumNumberOfLines/* + 1*/){//valid number of lines
				$this->_setTime('line');
				return false;
			}
			
		}
		
		$this->_setTime('line');
		return true;
	}
	
	protected function _validChanges($node_id){
		$this->_setTime('changes');
		
		$slicedRoute = array_slice($this->_route, 0, $node_id + 1);
		
		$lines = array();
		foreach($slicedRoute as $id => $node){
			$line_id = $node[0]['Line']['id'];
			
			if(!in_array($line_id, $lines)){
				$lines[] = $line_id;
			}
			
			if(count($lines) > 3){
				$this->_setTime('changes');
				return false;	
			}
		}
		$this->_setTime('changes');
		return true;
	}
	
	protected function _validNumberOfStations($node_id){
		$this->_setTime('numberOfStations');
		
		$slicedRoute = array_slice($this->_route, 0, $node_id + 1);
		
		$stations = array();
		foreach($slicedRoute as $id => $node){
			$stations = array_merge($stations, Set::extract('/Station/id', $node));
			
			if(count(array_unique($stations)) > $this->_minimumNumberOfStations + /*10*/6){
				$this->_setTime('numberOfStations');
				return false;	
			}
		}
		
		$this->_setTime('numberOfStations');
		return true;
	}
	
	protected function _validRegion(){
		$this->_setTime('regions');
		
		if(empty($this->__regions)){
			$this->__regions = $this->Station->find('list', array(
				'conditions' => array('Station.id' => array_merge($this->_stations['from'], $this->_stations['to'])),
				'group' => 'Station.region',
				'fields' => array('Station.region')
			));
		}

		$valid = array(
			'N' => array('N', 'V', 'E', 'CN', 'CV', 'CE', 'C'),
			'V' => array('V', 'N', 'S', 'CV', 'CN', 'CS', 'C'),
			'S' => array('S', 'V', 'E', 'CS', 'CV', 'CE', 'C'),
			'E' => array('E', 'N', 'S', 'CE', 'CN', 'CS', 'C'),
			'CN' => array('CN', 'CV', 'CE', 'N', 'V', 'E', 'C'),
			'CV' => array('CV', 'CN', 'CS', 'V', 'N', 'S', 'C'),
			'CS' => array('CS', 'CV', 'CE', 'S', 'V', 'E', 'C'),
			'CE' => array('CE', 'CN', 'CS', 'E', 'N', 'S', 'C'),
			'C' => array('C', 'CN', 'CV', 'CS', 'CE')
		);

		$validate = array();
		foreach($this->__regions as $region){
			$validate = array_merge($validate, $valid[$region]);
		}
		$validate = array_unique($validate);
		
		$this->_setTime('regions');
		
		return in_array($this->stationLine['Station']['region'], $validate);
	}
	
	protected function _validRadius(){
		return 
			$this->__distanceBetween(array(
				'lat' => $this->stationLine['Station']['lat'],
				'lng' => $this->stationLine['Station']['lng']
			), $this->coords['from']) < $this->__distance || 
			$this->__distanceBetween(array(
				'lat' => $this->stationLine['Station']['lat'],
				'lng' => $this->stationLine['Station']['lng']
			), $this->coords['to']) < $this->__distance
		;
	}
	
	protected function _validDistance($gettingFartherBy){
		return $gettingFartherBy <= 4;
	}
	
	protected function _validStationGroup($node_id){
		$this->_setTime('stationGroup');
		
		$slicedVisitedStationGroups = array_slice($this->_visitedStationGroups, 0, $node_id + 1);
		
		if(
			count(
				array_keys(
					Set::flatten($slicedVisitedStationGroups), 
					$this->stationLine['Station']['station_group_id']
				)
			) > 1
		){
			$this->_setTime('stationGroup');
			return false;
		}
		$this->_setTime('stationGroup');
		return true;
	}
		
	protected function _validNode($linesStack = array()){
		$this->_setTime('node');
		
		if($this->stationLine['Station']['node'] != 1){
			$this->_setTime('node');
			return false;
		}
		
		$stationGroupId = $this->stationLine['Station']['station_group_id'];
		$lineId = $this->stationLine['Line']['id'];
		
		if(empty($linesStack)){
			if(($nodeStationLinesByLine = Cache::read('NodeStationLineByLine.'.$stationGroupId.'.'.$lineId)) === false){
				if(($stationsByGroup = Cache::read('StationsByGroup.'.$stationGroupId)) === false){
					$stationsByGroup = $this->Station->find('list', array(
						'conditions' => array('Station.station_group_id' => $stationGroupId),
						'fields' => array('Station.id')
					));
					Cache::write('StationsByGroup.'.$stationGroupId, $stationsByGroup);
				}
				
				$nodeStationLinesByLine = $this->StationLine->find('all', array(
					'conditions' => array('StationLine.station_id' => $stationsByGroup),
					'order' => 'if(StationLine.line_id IN('.$lineId.'),0,1)'
				));
				Cache::write('NodeStationLineByLine.'.$stationGroupId.'.'.$lineId, $nodeStationLinesByLine);
			}
		} else {
			$implodedLinesStack = implode('_', $linesStack);
			
			if(($nodeStationLinesByLine = Cache::read('NodeStationLineByLine.'.$stationGroupId.'.'.$lineId.'.'.$implodedLinesStack)) === false){
				if(($stationsByGroup = Cache::read('StationsByGroup.'.$stationGroupId)) === false){
					$stationsByGroup = $this->Station->find('list', array(
						'conditions' => array('Station.station_group_id' => $stationGroupId),
						'fields' => array('Station.id')
					));
					Cache::write('StationsByGroup.'.$stationGroupId, $stationsByGroup);
				}
				
				$nodeStationLinesByLine = $this->StationLine->find('all', array(
					'conditions' => array('StationLine.station_id' => $stationsByGroup, 'StationLine.line_id' => $linesStack),
					'order' => 'if(StationLine.line_id IN('.$lineId.'),0,1)'
				));
				Cache::write('NodeStationLineByLine.'.$stationGroupId.'.'.$lineId.'.'.$implodedLinesStack, $nodeStationLinesByLine);
			}
		}
		
		/*if(!isset($this->__stationsByGroup[$stationGroupId])){
			$this->__stationsByGroup[$stationGroupId] = $this->Station->find('list', array(
				'conditions' => array('Station.station_group_id' => $stationGroupId),
				'fields' => array('Station.id')
			));
		}
		if(!isset($this->__nodeStationLinesByLine[$stationGroupId][$lineId])){
			$this->__nodeStationLinesByLine[$stationGroupId][$lineId] = $this->StationLine->find('all', array(
				'conditions' => array('StationLine.station_id' => $this->__stationsByGroup[$stationGroupId]),
				'order' => 'if(StationLine.line_id IN('.$lineId.'),0,1)'
			));
		}*/
		
		//$this->_nodeStationLines = $this->__nodeStationLinesByLine[$stationGroupId][$lineId];
		$this->_nodeStationLines = $nodeStationLinesByLine;
		
		$this->_setTime('node');
		return true;
	}
	
	protected function _saveRoute($node_id){
		$this->_setTime('save');
		
		$slicedRoute = array_slice($this->_route, 0, $node_id + 1);
		
		$route = array();
		foreach($slicedRoute as $nodeId => $node){
			if(
				$nodeId > 0 && 
				$slicedRoute[$nodeId][0]['StationLine']['id'] == 
				$slicedRoute[$nodeId - 1][count($slicedRoute[$nodeId - 1]) - 1]['StationLine']['id']
			){
				array_shift($slicedRoute[$nodeId]);
			}
			
			$route = array_merge($route, array_values($slicedRoute[$nodeId]));
			$line_id = $slicedRoute[$nodeId][0]['Line']['id'];
			//$numberOfStations = count(array_unique(array_diff(Set::extract('/Station/id', $node), $stations)));
			$numberOfStations = count($slicedRoute[$nodeId]);
			
			if(isset($this->_routeData[$this->routeId][$line_id])){
				$this->_routeData[$this->routeId][$line_id] += $numberOfStations;
			} else {
				$this->_routeData[$this->routeId][$line_id] = $numberOfStations;
			}
		}
		
		if(count($this->_routeData[$this->routeId]) < $this->_minimumNumberOfLines){
			$this->_minimumNumberOfLines = count($this->_routeData[$this->routeId]);
		}
		if(array_sum($this->_routeData[$this->routeId]) < $this->_minimumNumberOfStations){
			$this->_minimumNumberOfStations = array_sum($this->_routeData[$this->routeId]);
		}
		
		$this->_routeInfo[$this->routeId] = array(
			'number_of_lines' => count($this->_routeData[$this->routeId]),
			'number_of_nodes' => $node_id,
			'number_of_stations' => array_sum($this->_routeData[$this->routeId]),
		);

		$this->routes[$this->routeId] = array(
			'data' => array(
				'number_of_lines' => $this->_routeInfo[$this->routeId]['number_of_lines'],
				'number_of_nodes' => $this->_routeInfo[$this->routeId]['number_of_nodes'],
				'number_of_stations' => $this->_routeInfo[$this->routeId]['number_of_stations']
			),
			'route' => $route
		);
		$this->routeId++;
		
		if(count($slicedRoute) != 1){
			$reversedRoute = array_reverse($slicedRoute, true);
			foreach($reversedRoute as $id => $node){
				if($id > 0){
					if($node[0]['Line']['id'] == $reversedRoute[$id - 1][0]['Line']['id']){
						$this->_returnToNodeId = $id - 1;	
					} else {
						break;
					}
				}
			}
		}
		
		$this->_setTime('save');
	}
	
	//if you can get only with line 2 (key 2-2), then remove all x y 2 (key x-2); inner if conditions to list, for example, 1-2-3, 1-4-3 and not 1-2-5-3
	protected function _checkSimilarRoutes(){
		uasort($this->_routeData, array('Route', '_sortByNumberOfLinesAndStations'));

		$lines = array();
		foreach($this->_routeData as $routeId => $lineSet){
			$firstLine = key($lineSet);
			end($lineSet);
			$lastLine = key($lineSet);
			
			if(
				!array_key_exists("$lastLine-$lastLine", $lines) && 
				(
					!array_key_exists("$firstLine-$lastLine", $lines) ||
					(
						array_key_exists("$firstLine-$lastLine", $lines) &&
						count($lineSet) == count($lines["$firstLine-$lastLine"]) &&
						array_keys($lineSet) != array_keys($lines["$firstLine-$lastLine"])
					)
				)
			){
				$lines["$firstLine-$lastLine"] = $lineSet;
			} else {
				unset($this->_routeData[$routeId]);
				unset($this->routes[$routeId]);
			}
		}
	}
	
	protected function _optimizeRoutes(){
		foreach($this->_routeData as $routeId => $lineSet){
			if(
				array_sum($lineSet) > $this->_minimumNumberOfStations + /*10*/6 ||
				count($lineSet) > $this->_minimumNumberOfLines/* + 1*/
			){
				unset($this->_routeData[$routeId]);
				unset($this->routes[$routeId]);
			}
		}
	}
	
	protected static function _sortByNumberOfLinesAndStations($a, $b){
		if(count($a) - count($b) < 0){
			return -1;
		} elseif(count($a) - count($b) > 0){
			return 1;
		} elseif(
			count($a) - count($b) == 0 && 
			($ak = array_keys($a)) !== ($bk = array_keys($b))
		) {
			for($i = 0; $i < count($ak); $i++){
				if($ak[$i] > $bk[$i]){
					return 1;	
				} elseif($ak[$i] < $bk[$i]){
					return -1;
				}
			}
			return 0;
		} elseif(array_sum($a) < array_sum($b)){
			return -1;
		} elseif(array_sum($a) > array_sum($b)){
			return 1;
		} else {
			foreach($a as $lineId => $stations){
				if($stations > $b[$lineId]){
					return -1;
				} elseif($stations < $b[$lineId]){
					return 1;	
				}
			}
			return 0;
		}
	}
		
	private function __distanceBetween($from = array(), $to = array()){
		if(
			isset($from['lat']) &&
			isset($to['lng']) &&
			isset($from['lat']) &&
			isset($to['lng']) &&
			is_numeric($from['lat']) &&
			is_numeric($from['lng']) &&
			is_numeric($to['lat']) &&
			is_numeric($to['lng'])
		){
			return round(
				acos(
					sin(deg2rad($from['lat'])) *
					sin(deg2rad($to['lat'])) +
					cos(deg2rad($from['lat'])) *
					cos(deg2rad($to['lat'])) *
					cos(deg2rad($from['lng']) - deg2rad($to['lng']))
				) *
				6371000
			);
		}
		return false;
	}
	
	protected function _validCoords($coords){
		if(
			isset($coords['From']) &&
			isset($coords['To']) &&
			isset($coords['From']['lat']) &&
			isset($coords['From']['lng']) &&
			isset($coords['To']['lat']) &&
			isset($coords['To']['lng']) &&
			is_numeric($coords['From']['lat']) &&
			is_numeric($coords['From']['lng']) &&
			is_numeric($coords['To']['lat']) &&
			is_numeric($coords['To']['lng'])
		){
			$this->coords = array(
				'from' => array(
					'lat' => $coords['From']['lat'],
					'lng' => $coords['From']['lng']
				),
				'to' => array(
					'lat' => $coords['To']['lat'],
					'lng' => $coords['To']['lng']
				)
			);
			return true;
		}
		return false;
	}
	
	protected function _setTime($key){
		if(isset($this->_time[$key])){
			if(isset($this->time[$key])){
				$this->time[$key] = $this->time[$key] + (microtime(true) - $this->_time[$key]);
			} else {
				$this->time[$key] = microtime(true) - $this->_time[$key];
			}
			unset($this->_time[$key]);
		} else {
			$this->_time[$key] = microtime(true);	
		}
	}
	
	protected function _logFailedInit(){
		$type = 'error';
		$message = 'Initializarea rutei nu a putut avea loc';
		$this->_logWrite($type, $message);
	}
	
	protected function _logInvalidCoords(){
		$type = 'hijack';
		$message = 'Coordonatele primite pentru gasirea traseului sunt invalide';
		$this->_logWrite($type, $message);
	}
	
	protected function _logStationsNotFound(){
		$type = 'warning';
		$message = 'Nu au fost gasite statii in jurul punctului de plecare si/sau a punctului de sosire';
		$this->_logWrite($type, $message);
	}
	
	protected function _logStationLinesLookupNotFound(){
		$type = 'warning';
		$message = 'Nu au fost gasite statii prin care sa se caute ruta';
		$this->_logWrite($type, $message);
	}
	
	protected function _logRoutesNotFound(){
		$type = 'warning';
		$message = 'Nu au fost gasite trasee intre punctul de plecare si cel de sosire';
		$this->_logWrite($type, $message);
	}
	
	protected function _logRouteListNotFound(){
		$type = 'warning';
		$message = 'Nu au fost gasite trasee in baza de date intre punctul de plecare si cel de sosire';
		$this->_logWrite($type, $message);
	}
	
	protected function _logRouteDataNotFound(){
		$type = 'warning';
		$message = 'Nu au fost gasite datele asociate traseelor gasite in baza de date intre punctul de plecare si cel de sosire';
		$this->_logWrite($type, $message);
	}
	
	protected function _logNodeValidationFailed(){
		$type = 'db-cache';
		$message = 'Nu au putut fi validate nodurile';
		$this->_logWrite($type, $message);
	}
	
	protected function _logComputeFollowingStationLinesFailed(){
		$type = 'db-cache';
		$message = 'Nu au putut fi generate statiile urmatoare';
		$this->_logWrite($type, $message);
	}
	
	protected function _logComputeFalseStartEndsFailed(){
		$type = 'db-cache';
		$message = 'Nu au putut fi generate statiile de inceput/capetele false';
		$this->_logWrite($type, $message);
	}
	
	protected function _logComputeRoutesQueueFailed(){
		$type = 'db-cache';
		$message = 'Nu a putut fi generata lista cu rutele ce urmeaza sa fie generate';
		$this->_logWrite($type, $message);
	}
	
	protected function _logComputeRoutesFailed(){
		$type = 'db-cache';
		$message = 'Nu au putut fi generate toate rutele<br>';
		$message .= 'Au fost generate <code>'.$this->routeId.'</code> rute din <code>'.count($this->routesQueue).'</code> rute';
		$this->_logWrite($type, $message);
	}
	
	protected function _logComputeSuccess(){
		$type = 'db-cache';
		$message = 'Baza de date a fost generata cu succes! Mai jos ai un tabel cu timpii asociati acestei generari:';
		$message .= '<table>';
			$message .= '<tr><td>Timp total</td>';
			$message .= '<td>'.round($this->time['total'], 5).'s</td></tr>';
			
			$message .= '<tr><td>Validarea nodurilor</td>';
			$message .= '<td>'.round($this->time['validateNodes'], 5).'s</td></tr>';
			
			$message .= '<tr><td>Generarea statiilor urmatoare</td>';
			$message .= '<td>'.round($this->time['computeFollowingStationLines'], 5).'s</td></tr>';
			
			$message .= '<tr><td>Generarea statiilor de inceput/capetelor false</td>';
			$message .= '<td>'.round($this->time['computeFalseStartEnds'], 5).'s</td></tr>';
			
			$message .= '<tr><td>Generarea listei de rute</td>';
			$message .= '<td>'.round($this->time['computeRoutesQueue'], 5).'s</td></tr>';
			
			$message .= '<tr><td>Generarea rutelor</td>';
			$message .= '<td>'.round($this->time['computeRoutes'], 5).'s</td></tr>';
		$message .= '</table>';
		$this->_logWrite($type, $message);
	}
	
	protected function _logWrite($type, $message){
		CakeLog::write($type, $message);
	}
}
