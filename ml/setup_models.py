import numpy as np
from sklearn.linear_model import LinearRegression
from joblib import dump

# Dummy historical data (time index, e.g., 0..99)
X = np.arange(100).reshape(-1, 1)

# Dummy targets (Temperature, pH, Turbidity)
y_temp = 25 + np.sin(np.arange(100)/10)       # just some pattern
y_ph = 7 + 0.1*np.sin(np.arange(100)/5)
y_turb = 10 + 5*np.random.rand(100)

# Train simple LinearRegression models
temp_model = LinearRegression().fit(X, y_temp)
ph_model = LinearRegression().fit(X, y_ph)
turb_model = LinearRegression().fit(X, y_turb)

# Save models
dump(temp_model, "ml/temperature_model.joblib")
dump(ph_model, "ml/ph_model.joblib")
dump(turb_model, "ml/turbidity_model.joblib")

print("Dummy ML models created!")
