import os
from joblib import load
import json
import sys

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

temp_model = load(os.path.join(BASE_DIR, "temperature_model.joblib"))
ph_model   = load(os.path.join(BASE_DIR, "ph_model.joblib"))
turb_model = load(os.path.join(BASE_DIR, "turbidity_model.joblib"))

# Get argument from Laravel route if any
arg = int(sys.argv[1]) if len(sys.argv) > 1 else 1

# Dummy input for prediction
dummy_input = [[arg]]

predictions = {
    "temperature": float(temp_model.predict(dummy_input)[0]),
    "ph": float(ph_model.predict(dummy_input)[0]),
    "turbidity": float(turb_model.predict(dummy_input)[0])
}

print(json.dumps(predictions))  # THIS LINE IS CRUCIAL
