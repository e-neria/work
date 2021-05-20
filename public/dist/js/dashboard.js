$(document).ready(function() {
    chartColumn();
    chartPie();
});

function chartColumn(){
    Highcharts.chart('container-chart-columns', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Record Activities'
        },
        subtitle: {
            text: 'Total Activities: 53'
        },
        xAxis: {
            categories: [
                'Activities',
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Activities'
            }
        },
        /*tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },*/
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Finished',
            data: [50]

        }, {
            name: 'Working',
            data: [3]
        }]
    });
}

function chartPie(){
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
            text: 'Total Activities: 53'
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
                ['Finished', 45.0],
                {
                    name: 'Working',
                    y: 3,
                    sliced: true,
                    selected: true
                },
                /*['Others', 52]*/
            ]
        }]
    });
}