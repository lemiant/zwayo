<!doctype html>
<html ng-app="PartyApp">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.13/angular.min.js"></script>
  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.1/jquery.mobile-1.4.1.min.css">
  <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="http://code.jquery.com/mobile/1.4.1/jquery.mobile-1.4.1.min.js"></script>

  <script src="js/controllers.js"></script>
</head>
<body>

<div data-role="page" id="pageone">
  <div data-role="header">
    <h1>Zwayo</h1>
  </div>

  <div data-role="main" class="ui-content" ng-controller="PartyCtrl">
    <p>Insert Content Here</p>
    <div data-role="collapsibleset">
      <div data-role="collapsible" id="newParty" >
        <h3>New party</h3>
        <p>Test popdown</p>
      </div>

      <div data-role="collapsible">
        <h3></h3>
        <p></p>
      </div>
    </div>
    
    {{test}}
  </div>

  <div data-role="footer">
    <h1>Insert Footer Text Here</h1>
  </div>
</div> 

</body>
</html>