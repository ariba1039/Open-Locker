#!/usr/bin/env python
import asyncio
import logging
import sys

from pymodbus.server import StartAsyncTcpServer
from pymodbus.datastore import ModbusSequentialDataBlock
from pymodbus.datastore import ModbusSlaveContext, ModbusServerContext


# Basisadressen für die drei simulierten Schließfächer
COIL_BASE_ADDR = 1  # Coils: 1-3 für Öffnungsbefehle
HR_BASE_ADDR = 1    # Holding Register: 1-3 für Status

async def run_server():
    print("Starting server...")
    print("Hello server...")
    # Datenspeicher erstellen
    # Coils (Öffnungsbefehle) - initial alles 0 (alle Schließfächer geschlossen)
    coil_block = ModbusSequentialDataBlock(COIL_BASE_ADDR, [0] * 3)
    
    # Holding Register (Status) - initial alles 0 (alle Schließfächer geschlossen)
    hr_block = ModbusSequentialDataBlock(HR_BASE_ADDR, [0] * 3)
    
    # Slave-Kontext erstellen
    store = ModbusSlaveContext(
        di=None,  # keine discrete inputs
        co=coil_block,  # coils für Öffnungsbefehle
        hr=hr_block,  # holding register für Status
        ir=None,  # keine input register
    )
    
    # Server-Kontext erstellen (nur ein Slave mit Adresse 1)
    context = ModbusServerContext(slaves={1: store}, single=False)
    
    # Callback-Funktion für Änderungen an Coils (Öffnungsbefehle)
    async def on_coil_change():
        while True:
            await asyncio.sleep(0.5)  # Überprüfung alle 0,5 Sekunden
            for i in range(3):
                # Wenn der Öffnungsbefehl (Coil) gesetzt ist
                if context[1].getValues(2, i, 1)[0] == 1:
                    # Schließfach-Status auf offen setzen (Holding Register = 1)
                    context[1].setValues(3, i, [1])
                    print(f"Schließfach {i+1} wurde geöffnet")
                    # Öffnungsbefehl zurücksetzen
                    # await asyncio.sleep(1)  # 1 Sekunde simulierte Öffnungszeit
                    # context[1].setValues(2, i, [0])
    
    # Modbus TCP Server starten
    address = ("0.0.0.0", 502)
    print(f"Starte Modbus-TCP-Server auf {address[0]}:{address[1]}")
    
    # Starte Server
    server_task = asyncio.create_task(
        StartAsyncTcpServer(
            context=context,
            address=address
        )
    )

    await asyncio.sleep(0.5)

    # Öffnungsbefehl-Callback starten
    task = asyncio.create_task(on_coil_change())
    
    print("Modbus-Server erfolgreich gestartet!")
    print("Simulierte Schließfächer: 3")
    print("- Coils (1-3): Öffnungsbefehle")
    print("- Holding Register (1-3): Status (0=geschlossen, 1=offen)")
    
    # Status regelmäßig in Log ausgeben
    while True:
        statuses = []
        for i in range(3):
            current_context = context[1]
            status = current_context.getValues(3, i, 1)
            print(f"Status für Index {i}: {status}")
            #statuses.append("Offen" if status == 1 else "Geschlossen")
        
        #print(f"Schließfach-Status: 1: {statuses[0]}, 2: {statuses[1]}, 3: {statuses[2]}")
        await asyncio.sleep(5)  # Alle 5 Sekunden Status ausgeben

if __name__ == "__main__":
    asyncio.run(run_server()) 