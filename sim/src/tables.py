import pandas as pd
from flask import render_template
from src.support import get_file_paths, format_link


# Funzione per creare la matrice di somiglianza
def create_similarity_matrix(file_paths, comparator):
    n = len(file_paths)
    matrix = [[None for _ in range(n)] for _ in range(n)]

    for i in range(n):
        for j in range(i + 1, n):
            file1 = file_paths[i]
            file2 = file_paths[j]
            if file1 != file2:
                similarity = comparator(file1, file2)
                matrix[i][j] = similarity
                matrix[j][i] = similarity
    return matrix


def create_similarity_df(file_paths, base_path, comparator):
    matrix = create_similarity_matrix(file_paths, comparator)
    file_paths = [f.replace(base_path, "") for f in file_paths]
    df = pd.DataFrame(matrix, columns=file_paths)
    df['file_path'] = file_paths

    df['file'] = df['file_path'].str.split('/').str[-1]
    df['path'] = df['file_path'].str.split('/').str[1:-1].apply(lambda x: "/".join(x))
    # df['path'] = df['path']

    df.drop(columns=['file_path'], inplace=True)
    df.set_index(['path', 'file'], inplace=True)

    return df


def create_similarity_html_table_cl_es(classe, esercizio, comparator):
    base_path = f"/files/{classe}/{esercizio}/estratti"
    return create_similarity_html_table(base_path, comparator)


def create_similarity_html_table(base_path, comparator):
    file_paths = get_file_paths(base_path)
    # print(file_paths)
    # return base_path
    df = create_similarity_df(file_paths, base_path, comparator)

    df = df.fillna(0)  # Sostituisci NaN con 0 o un altro valore desiderato
    # # Applico il colore
    # styled_df = df.style.applymap(color_scale)
    # # Rimpiazzo gli zeri
    df.replace(0, "", inplace=True)

    for ir, filer in enumerate(file_paths):
        for ic, filec in enumerate(file_paths):
            df.iloc[ir, ic] = format_link(df.iloc[ir, ic], filer, filec)

    html = df.to_html(escape=False)
    return render_template("tabella.html", table_html=html)
