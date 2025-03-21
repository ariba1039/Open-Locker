FROM python:3.10-slim

WORKDIR /app

# Installiere PyModbus mit REPL-Unterstützung
RUN pip install pymodbus==3.8.6 pymodbus[repl]

# Kopiere den Source-Code
COPY . /app

# Port für Modbus-TCP freigeben
EXPOSE 502

# Start-Befehl
CMD ["python", "locker_simulator.py"] 