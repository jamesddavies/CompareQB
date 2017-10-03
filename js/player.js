var playerApp = angular.module("playerApp", ["ngSanitize", "ngRoute"]);
var baseURL = "php/playerSearch.php?search=";

playerApp.config(function($routeProvider, $locationProvider) {
  //$locationProvider.hashPrefix('');

  $routeProvider
    .when("/", {
      templateUrl: "main.html"
    })
    .when("/player/:param", {
      templateUrl: "player.html",
      controller: "Player"
    })
    .when("/compare", {
      templateUrl: "compare.html",
      controller: "Compare"
    })
    .when("/about", {
      templateUrl: "about.html"
    });

  //$locationProvider.html5Mode(true);
});

playerApp.controller("Player", function(
  $scope,
  $http,
  $routeParams,
  $timeout,
  charts
) {
  $scope.colorMain = "#aaa";
  $scope.colorSecondary = "#aaa";
  $scope.textColor = "#aaa";

  $scope.statsDescription = "Career Stats";

  $scope.search = $routeParams.param;
  //Request variables
  var request = {
    url: baseURL + $scope.search,
    method: "GET",
    headers: {
      "Content-Type": "application/json"
    }
  };
  $http(request).then(function(player) {
    $scope.player = player;
    console.log(player.data);
    $scope.stats = player.data.stats;
    $scope.league = player.data.league;
    $scope.pass = player.data.stats.pass;
    $scope.prevSearch = player.data.prevSearch;
    $scope.statsDescription = $scope.prevSearch.text;
    $scope.info = player.data.info;
    $scope.colorMain = $scope.info.colors.colorMain;
    $scope.colorSecondary = $scope.info.colors.colorSecondary;
    $scope.textColor = $scope.info.colors.textColor;

    //Chart colors, data + functions
    $scope.blue = "rgba(89,171,227,0.8)";
    $scope.red = "rgba(195,39,43,0.8)";
    $scope.green = "rgba(38, 166, 91, 0.8)";
    $scope.purple = "rgba(93, 63, 106, 0.8)";

    $scope.colors = [$scope.blue, $scope.red, $scope.green, $scope.purple];
    $scope.compPercData = [$scope.pass.cmp, $scope.pass.att - $scope.pass.cmp];
    $scope.gmYdsData = [
      $scope.pass.plus300,
      $scope.pass.plus200,
      $scope.pass.plus100,
      $scope.pass.under100
    ];
    $scope.tdPercData = [$scope.pass.att, $scope.pass.td];
    $scope.intPercData = [$scope.pass.att, $scope.pass.int];
    $scope.sackPercData = [$scope.pass.att, $scope.pass.sacks];

    charts.compPerc($scope.colors, $scope.compPercData);
    charts.gmYds($scope.colors, $scope.gmYdsData);
    charts.yearChart($scope.pass.years, $scope.pass.yardsByYear);
    charts.tdPerc($scope.colors, $scope.tdPercData);
    charts.intPerc($scope.colors, $scope.intPercData);
    charts.sackPerc($scope.colors, $scope.sackPercData);

    //Update stats

    //Arrays

    $scope.yearReq = [];
    $scope.gameReq = [];
    $scope.oppReq = [];

    $scope.addYear = function(year) {
      $scope.stats.games.forEach(function(game, i) {
        var index = $scope.gameReq.indexOf(game.year + "-" + game.week);
        if (index < 0 && game.year == year) {
          $scope.gameReq.push(game.year + "-" + game.week);
        }
      });
      console.log($scope.gameReq);
    };

    $scope.addGame = function(game) {
      var count = 0;
      $scope.gameReq.map(function(e, i) {
        if (e == game) {
          $scope.gameReq.splice(i, 1);
          count++;
        }
      });
      if (count === 0) {
        $scope.gameReq.push(game);
      }
      console.log($scope.gameReq);
    };

    $scope.addOpp = function(opp) {
      var count = 0;
      $scope.oppReq.map(function(e, i) {
        if (e == opp) {
          $scope.oppReq.splice(i, 1);
          count++;
        }
      });
      if (count === 0) {
        $scope.oppReq.push(opp);
      }
    };

    $scope.clearAll = function() {
      $scope.yearReq = [];
      $scope.gameReq = [];
      $scope.oppReq = [];
    };

    $scope.newReq = function() {
      $scope.query = "";

      $scope.oppReq.forEach(function(e) {
        $scope.query += "&opp[]=" + e;
      });

      $scope.gameReq.forEach(function(e) {
        $scope.query += "&game[]=" + e;
      });

      var request = {
        url: baseURL + $scope.search + $scope.query,
        method: "GET",
        headers: {
          "Content-Type": "application/json"
        }
      };
      $http(request).then(function(player) {
        $scope.player = player;
        console.log($scope.query);
        console.log(player.data);
        $scope.stats = player.data.stats;
        $scope.pass = player.data.stats.pass;
        $scope.info = player.data.info;
        $scope.prevSearch = player.data.prevSearch;
        $scope.statsDescription = $scope.prevSearch.text;

        charts.destroyPlayerCharts();
        $scope.compPercData = [
          $scope.pass.cmp,
          $scope.pass.att - $scope.pass.cmp
        ];
        $scope.gmYdsData = [
          $scope.pass.plus300,
          $scope.pass.plus200,
          $scope.pass.plus100,
          $scope.pass.under100
        ];
        $scope.tdPercData = [$scope.pass.att, $scope.pass.td];
        $scope.intPercData = [$scope.pass.att, $scope.pass.int];
        $scope.sackPercData = [$scope.pass.att, $scope.pass.sacks];

        charts.compPerc($scope.colors, $scope.compPercData);
        charts.gmYds($scope.colors, $scope.gmYdsData);
        charts.yearChart($scope.pass.years, $scope.pass.yardsByYear);
        charts.tdPerc($scope.colors, $scope.tdPercData);
        charts.intPerc($scope.colors, $scope.intPercData);
        charts.sackPerc($scope.colors, $scope.sackPercData);
      });

      $scope.yearReq = [];
      $scope.gameReq = [];
      $scope.oppReq = [];
      $(".select-all-year").removeClass("selected");
    };

    //UI FUNCTIONS - THESE NEED TO BE MOVED

    //Change Years chart on button click

    $(".chart-btn").click(function() {
      yearChart.destroy();
      $(".chart-btn").removeClass("active");
      $(this).addClass("active");
      var attr = $(this).attr("data-func");
      switch (attr) {
        case "yards":
          charts.yearChart($scope.pass.years, $scope.pass.yardsByYear);
          break;
        case "tds":
          charts.yearChart($scope.pass.years, $scope.pass.tdsByYear);
          break;
        case "ints":
          charts.yearChart($scope.pass.years, $scope.pass.intsByYear);
          break;
        case "attempts":
          charts.yearChart($scope.pass.years, $scope.pass.attsByYear);
          break;
        case "completions":
          charts.yearChart($scope.pass.years, $scope.pass.completionsByYear);
          break;
        case "compperc":
          charts.yearChart($scope.pass.years, $scope.pass.comppercByYear);
          break;
        case "tdperc":
          charts.yearChart($scope.pass.years, $scope.pass.TDpercByYear);
          break;
        case "yardsatt":
          charts.yearChart($scope.pass.years, $scope.pass.yardsAttByYear);
          break;
        case "sacks":
          charts.yearChart($scope.pass.years, $scope.pass.sacksByYear);
          break;
        case "sackyards":
          charts.yearChart($scope.pass.years, $scope.pass.sackYardsByYear);
          break;
        case "yardspersack":
          charts.yearChart($scope.pass.years, $scope.pass.yardsPerSackByYear);
          break;
        case "starts":
          charts.yearChart($scope.pass.years, $scope.pass.startsByYear);
          break;
        default:
          charts.yearChart($scope.pass.years, $scope.pass.yardsByYear);
          break;
      }
    });
  });
});

playerApp.controller("Compare", function(
  $scope,
  $http,
  $routeParams,
  $timeout,
  charts,
  $parse
) {
  //Colours for players to match charts
  $scope.blue = "rgba(89,171,227,0.6)";
  $scope.red = "rgba(195,39,43,0.6)";
  $scope.green = "rgba(38, 166, 91, 0.6)";
  $scope.purple = "rgba(93, 63, 106, 0.6)";

  //Initialising ng-model variables
  $scope.chartStat = "yards";
  $scope.compareYearCareer = "career";
  $scope.playerYears = {};

  function StartObject() {
    this.name = "";
    this.info = {
      colors: {
        colorMain: "#aaa",
        colorSecondary: "#eee",
        textColor: "#aaa"
      }
    };
  }
  $scope.players = {
    player1: new StartObject(),
    player2: new StartObject(),
    player3: new StartObject(),
    player4: new StartObject()
  };

  $scope.reqPlayer = function(n) {
    if (n == "1") var playerNum = $scope.players.player1;
    if (n == "2") var playerNum = $scope.players.player2;
    if (n == "3") var playerNum = $scope.players.player3;
    if (n == "4") var playerNum = $scope.players.player4;

    //Request variables
    var request = {
      url: baseURL + encodeURI(playerNum.name.toLowerCase()),
      method: "GET",
      headers: {
        "Content-Type": "application/json"
      }
    };
    
    $http(request).then(function(player) {
      if (n == "1") $scope.players.player1 = player.data;
      if (n == "2") $scope.players.player2 = player.data;
      if (n == "3") $scope.players.player3 = player.data;
      if (n == "4") $scope.players.player4 = player.data;

      console.log($scope.players);
      $scope.calculateTotalYears();

      $scope.playerYears[player.data.info.surname] = player.data.stats.allYears[0];

      $timeout(function() {
        if ($scope.compareYearCareer == "career"){
          charts.updateCareerCompareChart(
            $scope.players,
            $scope.chartStat,
            $scope.totalYears
          );
        } else if ($scope.compareYearCareer == "year"){
          charts.updateYearCompareChart(
            $scope.players,
            $scope.chartStat,
            $scope.playerYears
          );
        }
      });
    });

    $timeout(function() {
      $(".player-select .info").each(function(index, e) {
        if ($(this).position().top > 73) {
          $(this).css("font-size", "12px");
          $(this)
            .find("p:first-child")
            .css("font-size", "8.5px");
        }
      });
    }, 400);
  };

  $scope.erasePlayer = function(n) {
    var newObj = {
      name: "",
      info: {
        colors: {
          colorMain: "#aaa",
          colorSecondary: "#eee",
          textColor: "#aaa"
        }
      }
    };

    if (n == "1") $scope.players.player1 = newObj;
    if (n == "2") $scope.players.player2 = newObj;
    if (n == "3") $scope.players.player3 = newObj;
    if (n == "4") $scope.players.player4 = newObj;

    $scope.calculateTotalYears();

    $scope.playerYears[n] = "";

    if ($scope.compareYearCareer == "career"){
      charts.updateCareerCompareChart(
        $scope.players,
        $scope.chartStat,
        $scope.totalYears
      );
    } else if ($scope.compareYearCareer == "year"){
      charts.updateYearCompareChart(
        $scope.players,
        $scope.chartStat,
        $scope.playerYears
      );
    }
  };

  $scope.changeCareerStat = function() {
    $scope.calculateTotalYears();
    if ($scope.compareYearCareer == "career"){
      charts.updateCareerCompareChart(
        $scope.players,
        $scope.chartStat,
        $scope.totalYears
      );
    } else if ($scope.compareYearCareer == "year"){
      charts.updateYearCompareChart(
        $scope.players,
        $scope.chartStat,
        $scope.playerYears
      );
    }
  };

  $scope.calculateTotalYears = function() {
    $scope.totalYears = [];
    var array = [];

    angular.forEach($scope.players, function(player, index) {
      if (player.hasOwnProperty("stats")) {
        player.stats.allYears.forEach(function(year) {
          if (!array.includes(year)) {
            array.push(year);
          }
        });
        array = array.sort();
        //Check for/fill in empty years
        for (var j = 1; j < array.length; j++) {
          if (array[j] - array[j - 1] != 1) {
            var extraYear = array[j - 1] + 1;
            array.splice(j, 0, extraYear);
          }
        }
      }
    });

    $scope.totalYears = array;
  };

  $scope.updateYearCareer = function(){
    $timeout(function(){
      if ($scope.compareYearCareer == "career"){
        charts.updateCareerCompareChart(
          $scope.players,
          $scope.chartStat,
          $scope.totalYears
        );
      } else if ($scope.compareYearCareer == "year"){
        charts.updateYearCompareChart(
          $scope.players,
          $scope.chartStat,
          $scope.playerYears
        );
      }
    });
  }

  $scope.changePlayerYear = function(){
    charts.updateYearCompareChart(
      $scope.players,
      $scope.chartStat,
      $scope.playerYears
    );
  }
});

playerApp.service("charts", function() {
  this.destroyPlayerCharts = function() {
    compPercChart.destroy();
    tdPercChart.destroy();
    intPercChart.destroy();
    sackPercChart.destroy();
    gmYdsChart.destroy();
    yearChart.destroy();
  };

  //Chart setup

  this.updateCareerCompareChart = function(players, stat, totalYears) {
    //Career Compare chart data

    if (typeof careerCompareChart != "undefined") {
      careerCompareChart.destroy();
    }

    var dataCollection = [];

    angular.forEach(players, function(player, index) {
      if (player.hasOwnProperty("stats")) {
        var count = totalYears.indexOf(player.stats.allYears[0]);
        var arr = [];
        for (var i = 0; i < count; i++) {
          arr.push(null);
        }

        switch (stat) {
          case "yards":
            var statArray = player.stats.pass.yardsByYear;
            break;
          case "tds":
            var statArray = player.stats.pass.tdsByYear;
            break;
          case "ints":
            var statArray = player.stats.pass.intsByYear;
            break;
          case "attempts":
            var statArray = player.stats.pass.attsByYear;
            break;
          case "completions":
            var statArray = player.stats.pass.completionsByYear;
            break;
          case "compperc":
            var statArray = player.stats.pass.comppercByYear;
            break;
          case "tdperc":
            var statArray = player.stats.pass.TDpercByYear;
            break;
          case "yardsatt":
            var statArray = player.stats.pass.yardsAttByYear;
            break;
          case "sacks":
            var statArray = player.stats.pass.sacksByYear;
            break;
          case "sackyards":
            var statArray = player.stats.pass.sackYardsByYear;
            break;
          case "yardspersack":
            var statArray = player.stats.pass.yardsPerSackByYear;
            break;
          case "starts":
            var statArray = player.stats.pass.startsByYear;
            break;
          default:
            var statArray = player.stats.pass.yardsByYear;
            break;
        }

        var chartArray = arr.concat(statArray);

        dataCollection.push({
          label: player.info.surname,
          data: chartArray,
          backgroundColor: "rgba(255,255,255,0)",
          borderColor: player.info.colors.colorMain
        });
      }
    });

    var dataset = {
      labels: totalYears,
      datasets: dataCollection
    };

    this.careerCompare(dataset);
  };

  //Year compare chart data

  this.updateYearCompareChart = function(players,stat,playerYears){
    if (typeof yearCompareChart != "undefined") {
      yearCompareChart.destroy();
    }

    var dataCollection = [];
    var weekArray = [];
    var totalGames = 16;

    //Create label array
    for (var i = 0; i < totalGames; i++){
      weekArray.push("Game " + (i + 1));
    }

    //Create year array + data for each player

    angular.forEach(players, function(player,index){
      if (player.hasOwnProperty("stats")){
        var year = playerYears[player.info.surname];
        var gameArr = player.stats.games.filter(function(game){
          return game.year == year;
        })

        var tempArr = [];   

        for (var i = 0; i < totalGames; i++){
          tempArr.push(0);
        }
        
        gameArr.forEach(function(game,index){
          tempArr[game.week - 1] = game;
        });

        //Turn into array of chosen stat
        var gameStatArr = tempArr.map(function(week,index){
          if (typeof week != "object"){
            return week;
          } else {
            if (stat == "compperc"){
              return ((week.completions / week.attempts) * 100).toFixed(2);
            } else if (stat == "tdperc"){
              return ((week.tds / week.attempts) * 100).toFixed(2);
            } else if (stat == "yardspersack"){
              return (week.sackyards / week.sacks);
            } else {
              return week[stat];
            }
          }
        });

        //Get array of opposition for labels

        /*var oppArr = gameStatArr.map(function(week,index){
          if (typeof week != "object"){
            return "";
          } else {
            if (week.location == "@"){
              var homeaway = "@";
            } else {
              var homeaway = "vs";
            }
            return player.info.surname + " " + homeaway + " " + week.opp;
          }
        });*/
        
        dataCollection.push({
          label: player.info.surname,
          data: gameStatArr,
          backgroundColor: "rgba(255,255,255,0)",
          borderColor: player.info.colors.colorMain
        });
      }
    });

    var dataset = {
      labels: weekArray,
      datasets: dataCollection
    };

    this.yearCompare(dataset);
  }

  //COMPARE TOOL CHARTS

  this.careerCompare = function(data) {
    var ctx = document.getElementById("careerCompare").getContext("2d");
    var options = {};
    window.careerCompareChart = new Chart(ctx, {
      type: "line",
      data: data,
      options: options
    });
  };

  this.yearCompare = function(data) {
    var ctx = document.getElementById("yearCompare").getContext("2d");
    var options = {};
    window.yearCompareChart = new Chart(ctx, {
      type: "line",
      data: data,
      options: options
    });
  };

  //PLAYER STATS SCREEN CHARTS

  this.compPerc = function(colors, data) {
    //Completion percentage
    var ctx = document.getElementById("compAtt").getContext("2d");
    window.compPercChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: [" Completions", " Incompletions"],
        datasets: [
          {
            data: data,
            backgroundColor: colors
          }
        ]
      },
      options: {
        cutoutPercentage: 70,
        rotation: 0.5 * Math.PI,
        legend: {
          display: false
        }
      }
    });
  };

  this.tdPerc = function(colors, data) {
    //Completion percentage
    var ctx = document.getElementById("tdPerc").getContext("2d");
    window.tdPercChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: [" Pass Attempts", " Touchdowns"],
        datasets: [
          {
            data: data,
            backgroundColor: colors
          }
        ]
      },
      options: {
        cutoutPercentage: 70,
        rotation: 0.5 * Math.PI,
        legend: {
          display: false
        }
      }
    });
  };

  this.intPerc = function(colors, data) {
    //Completion percentage
    var ctx = document.getElementById("intPerc").getContext("2d");
    window.intPercChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: [" Pass Attempts", " Interceptions"],
        datasets: [
          {
            data: data,
            backgroundColor: colors
          }
        ]
      },
      options: {
        cutoutPercentage: 70,
        rotation: 0.5 * Math.PI,
        legend: {
          display: false
        }
      }
    });
  };

  this.sackPerc = function(colors, data) {
    //Completion percentage
    var ctx = document.getElementById("sackPerc").getContext("2d");
    window.sackPercChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: [" Pass Attempts", " Sacks"],
        datasets: [
          {
            data: data,
            backgroundColor: colors
          }
        ]
      },
      options: {
        cutoutPercentage: 70,
        rotation: 0.5 * Math.PI,
        legend: {
          display: false
        }
      }
    });
  };

  this.gmYds = function(colors, data) {
    //Games over 300 yds, etc
    var ctx = document.getElementById("gmYds").getContext("2d");
    window.gmYdsChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: [
          " 300+ Yards",
          " 200-299 Yards",
          " 100-199 Yards",
          " >100 Yards"
        ],
        datasets: [
          {
            data: data,
            backgroundColor: colors
          }
        ]
      },
      options: {
        cutoutPercentage: 70,
        rotation: 0.5 * Math.PI,
        legend: {
          display: false
        }
      }
    });
  };

  this.yearChart = function(labels, data) {
    //Totals for each year in the league
    var ctx = document.getElementById("years").getContext("2d");
    window.yearChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            data: data,
            backgroundColor: [
              "rgba(77,143,172,0.8)",
              "rgba(255,182,30,0.8)",
              "rgba(108,132,107,0.8)",
              "rgba(77,175,124,0.8)",
              "rgba(4,79,103,0.8)",
              "rgba(220,38,45,0.8)",
              "rgba(168,124,160,0.8)",
              "rgba(75,119,190,0.8)",
              "rgba(201,31,55,0.8)",
              "rgba(38,166,91,0.8)",
              "rgba(77,143,172,0.8)",
              "rgba(255,182,30,0.8)",
              "rgba(108,132,107,0.8)",
              "rgba(77,175,124,0.8)",
              "rgba(4,79,103,0.8)",
              "rgba(220,38,45,0.8)",
              "rgba(168,124,160,0.8)",
              "rgba(75,119,190,0.8)",
              "rgba(201,31,55,0.8)",
              "rgba(38,166,91,0.8)"
            ]
          }
        ]
      },
      options: {
        legend: {
          display: false
        },
        scales: {
          yAxes: [
            {
              display: false,
              ticks: {
                beginAtZero: true
              }
            }
          ]
        }
      }
    });
  };
});

playerApp.run(function($rootScope, $templateCache) {
  $rootScope.$on("$routeChangeStart", function(event, next, current) {
    if (typeof current !== "undefined") {
      $templateCache.remove(current.templateUrl);
    }
    $templateCache.removeAll();
  });
});
