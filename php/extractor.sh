#!/bin/bash

# Assicurati di essere nella directory in cui si trovano i file zip
cd $0
mkdir estratti

# Loop attraverso tutti i file zip nella directory corrente
for file_zip in *.zip; do
    # Estrai il file zip nella cartella con lo stesso nome
    unzip -d "estratti/${file_zip%.*}" "$file_zip"
done