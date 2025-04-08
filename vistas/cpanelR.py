import os
from ftplib import FTP

def is_file(line):
    return line.startswith('-')

# Configuraci贸n
ftp_host = 'solucionesintegralesjb.com'
ftp_user = 'soluciones'
ftp_password = '9pi#4+uDAiZ64O'
remote_folder = 'public_html/soluciones/files/txt'
local_folder = 'C:/Users/SISTEMA/Downloads/TXT'

# Conectar al servidor FTP
ftp = FTP(ftp_host)
ftp.login(ftp_user, ftp_password)

# Cambiar al directorio remoto
ftp.cwd(remote_folder)

# Obtener la lista de archivos y directorios en el directorio remoto
lines = []
ftp.retrlines('LIST', lines.append)

# Descargar cada archivo y guardarlo en la carpeta local
for line in lines:
    if is_file(line):
        file = line.split()[-1]
        with open(os.path.join(local_folder, file), 'wb') as local_file:
            ftp.retrbinary('RETR ' + file, local_file.write)
        ftp.delete(file)
    else:
        print(f"Skipping directory: {line}")

# Cerrar la conexi贸n FTP
ftp.quit()
