<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Registro de Consumo Energético</title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container">
    <div class="form-container">
      <h1>Registro de Consumo Energético</h1>
      <form id="consumption-form">
        <!-- Campo Campus -->
        <label for="campus">Campus</label>
        <select id="campus" name="campus" required>
          <option value="" disabled selected>Seleccione un campus</option>
          <option value="campus1">El Olivo</option>
          <option value="campus2">San Vicente de Paúl</option>
          <option value="campus3">Santa Mónica</option>
          <option value="campus3">Yuyucocha</option>
          <option value="campus3">Azaya</option>
          <option value="campus3">La Pradera</option>
          <option value="campus3">Colegio Universitario</option>

        </select>

        <!-- Campo Facultad 
        <label for="faculty">Facultad</label>
        <select id="faculty" name="faculty" required>
          <option value="" disabled selected>Seleccione una facultad</option>
          <option value="faculty1">Facultad 1</option>
          <option value="faculty2">Facultad 2</option>
          <option value="faculty3">Facultad 3</option>
        </select>
        -->

        <!-- Campo Kilovatios consumidos -->
        <label for="kilowatts">Kilovatios consumidos</label>
        <input type="number" id="kilowatts" name="kilowatts" placeholder="Ingrese los kW consumidos" required>

        <!-- Campo Periodo -->
        <label for="period">Periodo</label>
        <div class="period-container">
          <select id="month" name="month" required>
            <option value="" disabled selected>Mes</option>
            <option value="january">Enero</option>
            <option value="february">Febrero</option>
            <option value="march">Marzo</option>
            <option value="april">Abril</option>
            <option value="may">Mayo</option>
            <option value="june">Junio</option>
            <option value="july">Julio</option>
            <option value="august">Agosto</option>
            <option value="september">Septiembre</option>
            <option value="october">Octubre</option>
            <option value="november">Noviembre</option>
            <option value="december">Diciembre</option>
          </select>

          <input type="number" id="year" name="year" placeholder="Año" min="2000" max="2100" required>
          <input type="number" id="year" name="year" placeholder="Año" min="2000" max="2100" required>
        </div>

        <!-- Campo Ubicación específica 
        <label for="location">Ubicación específica</label>
        <input type="text" id="location" name="location" placeholder="Ingrese la ubicación" required>
-->
        <!-- Botones de acción -->
        <div class="form-buttons">
          <button type="submit" id="action-button">Registrar Consumo</button>
          <button type="button" id="update-button" class="hidden">Actualizar</button>
          <button type="button" id="delete-button" class="hidden">Eliminar</button>
          <button type="button" id="delete-button" class="">Generar Reporte</button>
        </div>
      </form>
    </div>

    <div class="chart-container">
      <h2>Consumo Energético por Campus</h2>
      <canvas id="energyChart"></canvas>
    </div>
  </div>

  <script src="scripts.js"></script>
</body>
</html>
