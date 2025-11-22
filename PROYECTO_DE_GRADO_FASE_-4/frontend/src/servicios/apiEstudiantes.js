class ServicioAPI {
  constructor() {
    this.urlBase = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';
  }

  async obtenerDashboardTiempoReal() {
    const respuesta = await fetch(`${this.urlBase}/dashboard/tiempo-real`);
    if (!respuesta.ok) {
      const text = await respuesta.text();
      throw new Error('Error obteniendo datos del dashboard: ' + text);
    }
    return await respuesta.json();
  }

  async registrarIntervencion(datosIntervencion) {
    const respuesta = await fetch(`${this.urlBase}/intervenciones`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(datosIntervencion)
    });

    if (!respuesta.ok) {
      const text = await respuesta.text();
      throw new Error('Error registrando intervención: ' + text);
    }

    return await respuesta.json();
  }

  async evaluarRiesgoEstudiante(estudianteId, datosEvaluacion) {
    const respuesta = await fetch(`${this.urlBase}/estudiantes/${estudianteId}/evaluar-riesgo`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(datosEvaluacion)
    });

    if (!respuesta.ok) {
      const text = await respuesta.text();
      throw new Error('Error evaluando riesgo del estudiante: ' + text);
    }

    return await respuesta.json();
  }
}

export default new ServicioAPI();
