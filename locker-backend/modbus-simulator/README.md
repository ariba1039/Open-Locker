# Modbus-Locker-Simulator

Dieser Simulator ermöglicht es, drei Schließfächer über Modbus zu simulieren.

## Funktion

Der Simulator stellt drei virtuelle Schließfächer zur Verfügung, die über das Modbus-Protokoll angesteuert werden können.

- **Port**: Standard Modbus-TCP Port 502
- **Web-Interface**: Port 8080
- **Slave-Adresse**: 1

## Register-Map

| Adresse | Typ | Beschreibung |
|---------|-----|-------------|
| 0 | Coil | Öffnungsbefehl für Schließfach 1 (1 = öffnen) |
| 1 | Coil | Öffnungsbefehl für Schließfach 2 (1 = öffnen) |
| 2 | Coil | Öffnungsbefehl für Schließfach 3 (1 = öffnen) |
| 0 | Holding Register | Status Schließfach 1 (0 = geschlossen, 1 = offen) |
| 1 | Holding Register | Status Schließfach 2 (0 = geschlossen, 1 = offen) |
| 2 | Holding Register | Status Schließfach 3 (0 = geschlossen, 1 = offen) |

## Verwendung

1. Zum Öffnen eines Schließfachs: Setze die entsprechende Coil auf 1
2. Die Coil wird automatisch nach einer Sekunde zurückgesetzt
3. Der Status des Schließfachs wird im Holding Register angezeigt
4. Der Server gibt alle 5 Sekunden den Status aller Schließfächer in den Logs aus

## Testen mit PyModbus REPL

PyModbus stellt ein REPL (Read-Evaluate-Print-Loop) Interface zur Verfügung, mit dem Sie den Modbus-Server interaktiv testen können.

### Starten des REPL

```bash
# Zugriff auf den Docker-Container
sail exec modbus-simulator bash

# Starten des REPL-Clients
python -m pymodbus.repl

# Alternativ mit direkter Verbindung
python -m pymodbus.repl --host modbus-simulator --port 502
```

### Beispiel-Befehle im REPL

```
# Verbindung zum Server herstellen
client.connect

# Status von Schließfach 1-3 abfragen
client.read_holding_registers address=0 count=3 slave=1

# Schließfach 1 öffnen
client.write_coil address=0 value=1 slave=1

# Status abfragen (1 = offen)
client.read_holding_registers address=0 count=1 slave=1
```

## Beispiel-Client (PHP)

```php
// Beispielcode mit php-modbus-library
$client = new \ModbusTcpClient\Network\BinaryStreamConnection('modbus-simulator', 502);
$client->connect();

// Schließfach 1 öffnen
$writer = new \ModbusTcpClient\Packet\ModbusFunction\WriteCoilRequest(0, true);
$client->sendRequest($writer);

// Status von Schließfach 1 abfragen
$request = new \ModbusTcpClient\Packet\ModbusFunction\ReadHoldingRegistersRequest(0, 1);
$response = $client->sendRequest($request);
$status = $response->getData()[0];  // 0 = geschlossen, 1 = offen

$client->disconnect();
``` 