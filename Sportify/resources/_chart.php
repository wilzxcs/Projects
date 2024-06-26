<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            // Load google charts
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            // Draw the chart and set the chart values
            function drawChart() {
            var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Work', 8],
            ['Friends', 2],
            ['Eat', 2],
            ['TV', 2],
            ['Gym', 2],
            ['Sleep', 8]
            ]);

            // Optional; add a title and set the width and height of the chart
            var options = {'title':'My Average Day', 'width':550, 'height':400};

            // Display the chart inside the <div> element with id="piechart"
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
            }
        </script>
        
        <div id="piechart"></div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <div>
            <canvas id="myChart"></canvas>
            <script>
            const labels = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
            ];
            const data = {
            labels: labels,
            datasets: [{
                label: 'My First dataset',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [0, 10, 5, 2, 20, 30, 45],
            }]
            };
            const config = {
                type: 'line',
                data: data,
                options: {}
            };
                myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        </script>
        
        </div>