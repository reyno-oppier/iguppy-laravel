# generate_data.py
import pandas as pd
import numpy as np
from datetime import datetime, timedelta

# Start time
start = datetime(2025, 1, 1, 0, 0)
rows = 600  # number of data points
interval_minutes = 10  # every 10 minutes

timestamps = [start + timedelta(minutes=i*interval_minutes) for i in range(rows)]
time_index = np.arange(rows)

# Create dummy sensor data with some pattern + noise
np.random.seed(42)
temperature = 25 + 0.01*time_index + 2*np.sin(2*np.pi*time_index/144) + np.random.normal(0,0.3,rows)
ph = 7.0 + 0.002*time_index + 0.2*np.sin(2*np.pi*time_index/288) + np.random.normal(0,0.05,rows)
turbidity = 10 + 0.005*time_index + 1.5*np.sin(2*np.pi*time_index/72) + np.random.normal(0,0.7,rows)

df = pd.DataFrame({
    "timestamp": timestamps,
    "time_index": time_index,
    "temperature": np.round(temperature,3),
    "ph": np.round(ph,3),
    "turbidity": np.round(turbidity,3)
})

df.to_csv("water_data.csv", index=False)
print("Saved water_data.csv with", len(df), "rows")
