let dashboardActivities = {};
$(document).ready(function() {
    dashboardActivities.getIndicators();
});

(function (){
    this.getIndicators = function(){
        jQuery.ajax({
            url: '/activities/getIndicators',
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                dashboardActivities.loadingCharts('container-chart-columns', 'Record Activities');
                dashboardActivities.loadingCharts('container-chart-pie', 'Record Activities');
            },
            complete: function (xhr, textStatus) {
            },
            success: function (data, textStatus, xhr) {
                dashboardActivities.setIndicators(data);
                dashboardActivities.chartColumn(data);
                dashboardActivities.chartPie(data);
            },
            error: function (xhr, textStatus, errorThrown) {
            }
        });
    }

    this.setIndicators = function(data){
        var dataSeries = this.getDataSeries(data.body);

        $('.totalActivitiesFinished').html(dataSeries.finishedActivities);
        $('.totalSubActivitiesFinished').html(dataSeries.finishedSubActivities);
        $('.totalActivitiesWorking').html(dataSeries.workingActivities);
        $('.totalSubActivitiesWorking').html(dataSeries.workingSubActivities);
    }

    this.chartColumn = function(data){
        var dataSeries = this.getDataSeries(data.body)

        Highcharts.chart('container-chart-columns', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Record Activities'
            },
            subtitle: {
                text: 'Total Activities: ' + dataSeries.totalActivities
            },
            xAxis: {
                categories: ['Finished', 'Working']
            },
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'Total Activities'
                }
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.x + '</b><br/>' +
                        this.series.name + ': ' + this.y + '<br/>' +
                        'Total: ' + this.point.stackTotal;
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },
            series: [{
                name: 'Activities',
                data: [dataSeries.finishedActivities, dataSeries.workingActivities],
                stack: 'male'
            }, {
                name: 'SubActivities',
                data: [dataSeries.finishedSubActivities, dataSeries.workingSubActivities],
                stack: 'male'
            }],
            credits: {
                enabled: false
            },
            colors: ['#7cb5ec', '#90ed7d']
        });
    }

    this.chartPie = function(data){
        var dataSeries = this.getDataSeries(data.body)

        Highcharts.chart('container-chart-pie', {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Record Activities'
            },
            subtitle: {
                text: 'Total Activities: ' + dataSeries.totalActivities
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Activities',
                data: [
                    ['Activities Finished', dataSeries.finishedActivities],
                    ['Sub-Activities Finished', dataSeries.finishedSubActivities],
                    ['Working Sub-Activities', dataSeries.workingSubActivities],
                    {
                        name: 'Working Activities',
                        y: dataSeries.workingActivities,
                        sliced: true,
                        selected: true
                    },
                ]
            }],
            credits: {
            enabled: false
        }
        });
    }

    this.getDataSeries = function(data){
        let totalActivities = 0;
        let totalSubActivities = 0;
        let finishedActivities = 0;
        let finishedSubActivities = 0;
        let workingActivities = 0;
        let workingSubActivities = 0;

        $.each(data, function (key, value){
            totalActivities += value.activities;
            if(value.type == 0){
                if(value.status == 0){
                    workingActivities += value.activities;
                }else{
                    finishedActivities += value.activities;
                }
            }else{
                //totalSubActivities += value.activities;
                if(value.status == 0){
                    workingSubActivities += value.activities;
                }else{
                    finishedSubActivities += value.activities;
                }
            }
        });

        return {
            'totalActivities': totalActivities,
            //'totalSubActivities': totalSubActivities,
            'finishedActivities': finishedActivities,
            'finishedSubActivities': finishedSubActivities,
            'workingActivities': workingActivities,
            'workingSubActivities': workingSubActivities
        }
    }

    this.loadingCharts = function(chart, title){
        Highcharts.chart(chart, {
            title: {
                text: title
            }
        }).showLoading();
    }
}).apply(dashboardActivities);