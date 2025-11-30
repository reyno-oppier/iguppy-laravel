const functions = require("firebase-functions");
const admin = require("firebase-admin");
admin.initializeApp();

// Listen for changes in /sensors
exports.pushHistoryOnChange = functions.database
    .ref('/sensors')
    .onUpdate((change, context) => {
        const before = change.before.val();
        const after = change.after.val();

        // Check if any main parameter changed
        if (
            before.temperature === after.temperature &&
            before.ph === after.ph &&
            before.turbidity === after.turbidity
        ) {
            return null; // No change in main data
        }

        const historyRef = change.after.ref.child('history');
        const newRecord = {
            waktu: new Date().toISOString(),
            temperature: after.temperature,
            ph: after.ph,
            turbidity: after.turbidity
        };

        // Push new history record
        return historyRef.push(newRecord)
            .then(() => {
                console.log("New history record added:", newRecord);
                return null;
            })
            .catch((error) => {
                console.error("Error adding history record:", error);
            });
    });
