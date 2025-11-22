import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
import joblib

class PredictorRiesgoDesercion:
    def __init__(self):
        self.modelo = None
        self.caracteristicas = [
            'rendimiento_academico',
            'porcentaje_asistencia',
            'nivel_estres',
            'situacion_economica',
            'apoyo_familiar',
            'habitos_estudio'
        ]

    def preparar_datos(self, dataframe: pd.DataFrame):
        X = dataframe[self.caracteristicas]
        y = dataframe['riesgo_desercion']
        return X, y

    def entrenar_modelo(self, X, y):
        X_train, X_test, y_train, y_test = train_test_split(
            X, y, test_size=0.2, random_state=42
        )

        self.modelo = RandomForestClassifier(
            n_estimators=100,
            max_depth=10,
            random_state=42
        )

        self.modelo.fit(X_train, y_train)

        precision = self.modelo.score(X_test, y_test)
        print(f"Precisión del modelo: {precision:.2%}")
        return precision

    def predecir_riesgo_estudiante(self, datos_estudiante: pd.DataFrame):
        if self.modelo is None:
            raise ValueError("El modelo debe ser entrenado o cargado primero")

        probabilidades = self.modelo.predict_proba(datos_estudiante)
        nivel_riesgo_idx = int(np.argmax(probabilidades, axis=1)[0])
        confianza = float(np.max(probabilidades, axis=1)[0])

        return {
            'nivel_riesgo_idx': nivel_riesgo_idx,
            'confianza': confianza,
            'probabilidades': probabilidades[0].tolist()
        }

    def guardar_modelo(self, ruta_archivo: str):
        joblib.dump(self.modelo, ruta_archivo)

    def cargar_modelo(self, ruta_archivo: str):
        self.modelo = joblib.load(ruta_archivo)
