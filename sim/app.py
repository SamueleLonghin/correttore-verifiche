# app/views.py
from flask import Flask, render_template

from src.comparators import compare_files, cosine_compare_files, spacy_compare_file
from src.config import UPLOAD_FOLDER
from src.support import *
from src.tables import create_similarity_html_table_cl_es
from src.upload import render_home, render_action

app = Flask(__name__)

app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER


@app.route('/table/<string:classe>/<string:esercizio>')
def base_table(classe, esercizio):
    return create_similarity_html_table_cl_es(classe, esercizio, compare_files)


@app.route('/cosine-table/<string:classe>/<string:esercizio>')
def cosine_table(classe, esercizio):
    return create_similarity_html_table_cl_es(classe, esercizio, cosine_compare_files)


@app.route('/spacy-table/<string:classe>/<string:esercizio>')
def spacy_table(classe, esercizio):
    return create_similarity_html_table_cl_es(classe, esercizio, spacy_compare_file)


@app.route('/')
def home():
    return render_home()


@app.route('/send', methods=['POST'])
def action_table():
    return render_action()


@app.route('/compare/<string:a>/<string:b>')
def compare(a, b):
    a = a.replace("+!+", '/')
    b = b.replace("+!+", '/')
    contenta = read_file_content(a)
    contentb = read_file_content(b)

    return render_template("compare.html", file1=a, file2=b, content_file1=contenta, content_file2=contentb)


if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0')
