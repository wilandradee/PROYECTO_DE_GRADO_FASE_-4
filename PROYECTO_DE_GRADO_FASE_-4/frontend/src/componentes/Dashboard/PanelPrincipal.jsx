import React, { useState, useEffect } from 'react';
import {
  Box,
  Grid,
  Card,
  CardContent,
  Typography,
  Button,
  Alert,
  CircularProgress
} from '@mui/material';
import PeopleIcon from '@mui/icons-material/People';
import WarningIcon from '@mui/icons-material/Warning';
import TrendingUpIcon from '@mui/icons-material/TrendingUp';
import NotificationsActiveIcon from '@mui/icons-material/NotificationsActive';
import RefreshIcon from '@mui/icons-material/Refresh';
import ServicioAPI from '../../servicios/apiEstudiantes';

const TarjetaMetrica = ({ icono, titulo, valor, subtitulo }) => (
  <Card sx={{ height: '100%' }}>
    <CardContent>
      <Box display="flex" alignItems="center" mb={2}>
        {icono}
        <Typography variant="h6" sx={{ ml: 1 }}>
          {titulo}
        </Typography>
      </Box>
      <Typography variant="h4" gutterBottom>
        {valor === null ? <CircularProgress size={24} /> : valor}
      </Typography>
      {subtitulo && (
        <Typography variant="body2" color="text.secondary">
          {subtitulo}
        </Typography>
      )}
    </CardContent>
  </Card>
);

const PanelPrincipal = () => {
  const [datosDashboard, setDatosDashboard] = useState(null);
  const [cargando, setCargando] = useState(true);
  const [ultimaActualizacion, setUltimaActualizacion] = useState(null);

  useEffect(() => {
    obtenerDatosDashboard();
    const intervalo = setInterval(obtenerDatosDashboard, 30000);
    return () => clearInterval(intervalo);
  }, []);

  const obtenerDatosDashboard = async () => {
    setCargando(true);
    try {
      const data = await ServicioAPI.obtenerDashboardTiempoReal();
      setDatosDashboard(data);
      setUltimaActualizacion(data.ultima_actualizacion || new Date().toISOString());
    } catch (err) {
      console.error('Error obteniendo datos del dashboard:', err);
    } finally {
      setCargando(false);
    }
  };

  const manejarIntervencionInmediata = async (estudianteId) => {
    try {
      await ServicioAPI.registrarIntervencion({
        estudiante_id: estudianteId,
        tipo: 'inmediata',
        observaciones: 'Intervención iniciada desde dashboard'
      });
      obtenerDatosDashboard();
    } catch (error) {
      console.error('Error registrando intervención:', error);
    }
  };

  return (
    <Box p={3}>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={4}>
        <Typography variant="h4" component="h1">
          Panel de Control - Bienestar Estudiantil
        </Typography>
        <Box display="flex" alignItems="center" gap={2}>
          <Typography variant="body2" color="text.secondary">
            {ultimaActualizacion && `Última actualización: ${new Date(ultimaActualizacion).toLocaleTimeString()}`}
          </Typography>
          <Button startIcon={<RefreshIcon />} onClick={obtenerDatosDashboard} disabled={cargando} variant="outlined">
            {cargando ? 'Actualizando...' : 'Actualizar'}
          </Button>
        </Box>
      </Box>

      <Grid container spacing={3} mb={4}>
        <Grid item xs={12} sm={6} md={3}>
          <TarjetaMetrica
            icono={<PeopleIcon />}
            titulo="Total Estudiantes"
            valor={datosDashboard?.metricas?.total_estudiantes ?? null}
          />
        </Grid>
        <Grid item xs={12} sm={6} md={3}>
          <TarjetaMetrica
            icono={<WarningIcon />}
            titulo="Alto Riesgo"
            valor={datosDashboard?.metricas?.estudiantes_alto_riesgo ?? null}
            subtitulo="Requieren intervención inmediata"
          />
        </Grid>
        <Grid item xs={12} sm={6} md={3}>
          <TarjetaMetrica
            icono={<TrendingUpIcon />}
            titulo="Riesgo Medio"
            valor={datosDashboard?.metricas?.estudiantes_medio_riesgo ?? null}
            subtitulo="Seguimiento cercano"
          />
        </Grid>
        <Grid item xs={12} sm={6} md={3}>
          <TarjetaMetrica
            icono={<NotificationsActiveIcon />}
            titulo="Alertas Activas"
            valor={datosDashboard?.metricas?.alertas_activas ?? null}
            subtitulo="Intervenciones pendientes"
          />
        </Grid>
      </Grid>

      <Card>
        <CardContent>
          <Typography variant="h5" gutterBottom>
            Alertas Recientes
          </Typography>

          {(!datosDashboard || datosDashboard.alertas_recientes?.length === 0) ? (
            <Alert severity="success">No hay alertas activas en este momento</Alert>
          ) : (
            datosDashboard.alertas_recientes.map((alerta) => (
              <Alert
                key={alerta.id}
                severity={alerta.prioridad === 'alta' ? 'error' : 'warning'}
                action={
                  <Button color="inherit" size="small" onClick={() => manejarIntervencionInmediata(alerta.estudiante.id)}>
                    INTERVENIR
                  </Button>
                }
                sx={{ mb: 1 }}
              >
                <strong>{alerta.estudiante.nombre}</strong> - {alerta.mensaje}
                <Typography variant="body2" component="div">
                  Programa: {alerta.estudiante.programa}
                </Typography>
              </Alert>
            ))
          )}
        </CardContent>
      </Card>
    </Box>
  );
};

export default PanelPrincipal;
