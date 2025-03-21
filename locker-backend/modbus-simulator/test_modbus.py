#!/usr/bin/env python
"""
Testskript für den Modbus-Locker-Simulator
"""
import asyncio
from pymodbus.client import AsyncModbusTcpClient
from pymodbus.constants import Endian
from pymodbus.payload import BinaryPayloadDecoder
from pymodbus.exceptions import ModbusException

async def test_lockers():
    """Testet die simulierten Schließfächer"""
    print("Verbinde mit Modbus-Simulator...")
    
    # Mit dem Simulator verbinden
    client = AsyncModbusTcpClient('modbus-simulator', port=502)
    connected = await client.connect()
    
    if not connected:
        print("Verbindung fehlgeschlagen!")
        return
    
    print("Verbindung hergestellt!")
    
    try:
        # Aktuelle Status abfragen
        print("\nAktuelle Status der Schließfächer:")
        try:
            # Versuchen, Coils zu lesen statt Holding-Register
            response = await client.read_coils(address=0, count=3, slave=1)
            if response.isError():
                print(f"Fehler beim Lesen der Coils: {response}")
            else:
                for i, status in enumerate(response.bits):
                    print(f"Schließfach {i+1}: {'Offen' if status else 'Geschlossen'}")
        except ModbusException as e:
            print(f"Modbus-Fehler beim Lesen der Coils: {e}")
            # Versuchen wir es mit Input-Registern als Alternative
            try:
                response = await client.read_input_registers(address=0, count=3, slave=1)
                if response.isError():
                    print(f"Fehler beim Lesen der Input-Register: {response}")
                else:
                    for i, status in enumerate(response.registers):
                        print(f"Schließfach {i+1}: {'Offen' if status == 1 else 'Geschlossen'}")
            except ModbusException as e:
                print(f"Modbus-Fehler beim Lesen der Input-Register: {e}")
        
        # Schließfach 1 öffnen
        print("\nSchließfach 1 wird geöffnet...")
        try:
            response = await client.write_coil(address=0, value=True, slave=1)
            if response.isError():
                print(f"Fehler beim Öffnen: {response}")
            else:
                print("Befehl zum Öffnen gesendet")
        except ModbusException as e:
            print(f"Modbus-Fehler beim Öffnen: {e}")
        
        # Kurz warten, damit das Schließfach reagieren kann
        await asyncio.sleep(1.5)
        
        # Status von Schließfach 1 überprüfen
        print("\nStatus nach dem Öffnen:")
        try:
            response = await client.read_coils(address=0, count=1, slave=1)
            if response.isError():
                print(f"Fehler beim Lesen: {response}")
            else:
                status = response.bits[0]
                print(f"Schließfach 1: {'Offen' if status else 'Geschlossen'}")
        except ModbusException as e:
            print(f"Modbus-Fehler: {e}")
        
        # Schließfach 2 öffnen
        print("\nSchließfach 2 wird geöffnet...")
        try:
            response = await client.write_coil(address=1, value=True, slave=1)
            if response.isError():
                print(f"Fehler beim Öffnen: {response}")
            else:
                print("Befehl zum Öffnen gesendet")
        except ModbusException as e:
            print(f"Modbus-Fehler: {e}")
        
        # Kurz warten, damit das Schließfach reagieren kann
        await asyncio.sleep(1.5)
        
        # Alle Status überprüfen
        print("\nAktuelle Status aller Schließfächer:")
        try:
            response = await client.read_coils(address=0, count=3, slave=1)
            if response.isError():
                print(f"Fehler beim Lesen: {response}")
            else:
                for i, status in enumerate(response.bits):
                    print(f"Schließfach {i+1}: {'Offen' if status else 'Geschlossen'}")
        except ModbusException as e:
            print(f"Modbus-Fehler: {e}")
        
    finally:
        # Verbindung schließen
        print("\nVerbindung wird geschlossen...")
        try:
            if client and hasattr(client, 'connected') and client.connected:
                # In neueren pymodbus-Versionen gibt close() möglicherweise keinen awaitable zurück
                close_result = client.close()
                if close_result is not None:
                    await close_result
                else:
                    # Wenn close() nichts zurückgibt, müssen wir nichts awaiten
                    print("Client wurde geschlossen")
        except Exception as e:
            print(f"Fehler beim Schließen der Verbindung: {e}")

if __name__ == "__main__":
    asyncio.run(test_lockers()) 