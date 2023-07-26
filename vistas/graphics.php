<?php
require_once "./php/main.php";

?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
<?php
        $consulta = conexion();
        $consulta = $consulta->query("SELECT producto_nombre, producto_stock FROM producto");
          while ($resultado = $consulta->fetch()) {
            echo "['" .$resultado['producto_nombre']."', " .$resultado['producto_stock']."],";
          }
            

?>
        ]);

        var options = {
          title: 'Productos en Inventario'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>