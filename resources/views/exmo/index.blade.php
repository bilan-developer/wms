<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div id="chart_div"></div>

<script>
    google.charts.load('current', {packages: ['corechart', 'line']});
    var info = [[0,0], [1,1]];
    $(document).ready(function(){
        getData();
    });
    
    function getData() {
        setTimeout(function() {
            $.ajax({
                type: "get",
                url: '/exmo-show',
                success: function(data){
                    info = data;
                    google.charts.setOnLoadCallback(drawBasic);
                }
            });
            getData();
        }, 10000);
    }



    function drawBasic() {

        var data = new google.visualization.DataTable();
        data.addColumn('number', 'X');
        data.addColumn('number', 'Dogs');

        data.addRows(info);

        var options = {
            hAxis: {
                title: 'Time'
            },
            vAxis: {
                title: 'Popularity'
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);
    }
</script>