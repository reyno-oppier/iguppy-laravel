# train_model.py
import pandas as pd
from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split
from joblib import dump
from sklearn.metrics import mean_absolute_error

df = pd.read_csv("water_data.csv")
# Feature: use time_index. For better results you can add lag features later.
X = df[["time_index"]].values
y_temp = df["temperature"].values
y_ph = df["ph"].values
y_turb = df["turbidity"].values

def train_and_save(X, y, name):
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42, shuffle=False)
    model = RandomForestRegressor(n_estimators=100, random_state=42)
    model.fit(X_train, y_train)
    pred = model.predict(X_test)
    err = mean_absolute_error(y_test, pred)
    print(f"{name} MAE: {err:.4f}")
    dump(model, f"{name}_model.joblib")

train_and_save(X, y_temp, "temperature")
train_and_save(X, y_ph, "ph")
train_and_save(X, y_turb, "turbidity")

print("Models saved: temperature_model.joblib, ph_model.joblib, turbidity_model.joblib")
