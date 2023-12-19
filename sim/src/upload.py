from flask import render_template, request
import tempfile
import os

from src.support import extract_zip, extract_nested_zips, convert_formats
from src.comparators import cosine_compare_files, comparators, compare_files
from src.tables import create_similarity_html_table


def render_home():
    print("CIAOOO")
    return render_template('index.html')


def render_action():
    password = os.environ.get('PASSWORD', 'psw')

    if request.form['password'] != password:
        return render_template('index.html', error='Password Errata, ritenta')

    if 'file' not in request.files:
        return render_template('index.html', error='No file part')

    file = request.files['file']

    # Controlla se è stato selezionato un file
    if file.filename == '':
        return render_template('index.html', error='No selected file')

    # Salva il file nella cartella temporanea
    temp_folder = tempfile.mkdtemp()
    file_path = os.path.join(temp_folder, file.filename)
    file.save(file_path)

    # Estrai il file zip principale
    extract_zip(file_path, temp_folder)

    subdirectories = [d for d in os.listdir(temp_folder) if os.path.isdir(os.path.join(temp_folder, d))]

    while len(subdirectories) == 1:
        temp_folder = os.path.join(temp_folder, subdirectories[0])
        print("Nuova dir:", temp_folder, "Ignorata:", subdirectories[0])
        subdirectories = [d for d in os.listdir(temp_folder) if os.path.isdir(os.path.join(temp_folder, d))]

        # for item in os.listdir(source):
        #     shutil.move(os.path.join(source, item), os.path.join(directory, item))
        # os.rmdir(source)

    # Estrai gli archivi zip nidificati
    extract_nested_zips(temp_folder)

    # Converti i formati strani (docx) to txt
    convert_formats(temp_folder)
    # Fai qualcosa con i file estratti (es. stampa il loro elenco)
    print("Files estratti:", os.listdir(temp_folder))

    # Ottengo l'algoritmo
    algorithm = request.form['algorithm']
    # Ottengo il comparatore
    comparator = comparators.get(algorithm, compare_files)
    return create_similarity_html_table(temp_folder, comparator)
