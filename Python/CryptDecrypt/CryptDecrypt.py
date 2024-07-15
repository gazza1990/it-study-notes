# Si può pensare di creare un programma che prende in input una stringa
# e la restituisce criptata o decriptata in base alla richiesta.
# In questo modo potrei inserire nei file di configurazione la stringa
# criptata, per poi richiamare dallo script il file eseguibile passandogli
# questa stringa in modo da farmi restituire quella decriptata.
#
# result = subprocess.run(["<your_exe>"], capture_output=True)
# output = result.stdout
#
# ESEMPIO
# Devo collegarmi a un FTP
# Cripto la password attraverso il file .exe
# Inserisco la password nel file di configurazione
# Creo uno script che necessita di collegarsi all'FTP
# Quando mi serve la password, dallo script recupero la stringa criptata dal
# file di configurazione e la dò in pasto all'exe che mi restituirà la password
#
# In questo modo l'algoritmo di criptazione / la chiave si trova nel file eseguibile

import sys

defCharList = [58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121]

def splitta(val, maxval, giri):
    if val > maxval:
        return splitta(val-maxval, maxval, giri+1)
    else:
        return (val, giri)

def cripta(password, charlist):
    passlen = len(password)
    # charlist = [48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121]
    conv1 = []
    conv2 = []

    # Converto in interi
    for char in password:
        conv1.append(ord(char))

    for x in range(0, len(conv1), 2):
        if x != len(conv1)-1:
            v1 = conv1[x]
            conv1[x] = conv1[x+1]
            conv1[x+1]=v1

    conv1.reverse()

    first = conv1[0]
    for x in range(0, len(conv1)):
        if x != len(conv1)-1:
            conv1[x] = conv1[x] + conv1[x+1]
        else:
            conv1[x] = conv1[x] + first

    key = []
    conv1.append(first)

    def splitta(val, maxval, giri):
        if val > maxval:
            return splitta(val-maxval, maxval, giri+1)
        else:
            return (val, giri)

    for x in conv1:
        asd = splitta(x, len(charlist)-1,1)
        conv2.append(asd[0])
        key.append(asd[1])

    strConv2 = ''
    strKey = ''

    for x in conv2:
        strConv2 += str(chr(charlist[x]))
    for x in key:
        strKey += str(chr(charlist[x]))

    strDef = ''
    for x in range(0,len(strConv2)):
        strDef += strConv2[x] + strKey[x]

    print(strDef)


def decripta(strDef, charlist):

    dconv1 = []
    dconv2 = []

    for x in range(0, len(strDef), 2):
        dconv1.append(charlist.index(ord(strDef[x])) + (len(charlist)-1) * (charlist.index(ord(strDef[x+1]))-1))

    dconv1.reverse()

    for x in range(1, len(dconv1)):
        dconv1[x]=dconv1[x]-dconv1[x-1]
    dconv1.pop(0)

    for x in range(0, len(dconv1), 2):
        if x != len(dconv1)-1:
            v1 = dconv1[x]
            dconv1[x] = dconv1[x+1]
            dconv1[x+1]=v1

    pwd = ''
    for x in dconv1:
        pwd += str(chr(x))

    print(pwd)

if len(sys.argv) > 2:
    if len(sys.argv) == 4:
        chars = sys.argv[3]
        lista = []
        for char in chars:
            lista.append(ord(char))
        listaDef = list(set(lista))
    else:
        listaDef = defCharList
    
    if(sys.argv[1] == "-c"):
        cripta(sys.argv[2], listaDef)
    elif(sys.argv[1] == "-d"):
        decripta(sys.argv[2], listaDef)
    else:
        print("Qualcosa è andato storto")
else:
    print("Inserire gli argomenti corretti")

