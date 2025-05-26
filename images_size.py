import os
from PIL import Image

FOLDER = "."  # Cartella corrente

# Trova tutti i file .jpg/.jpeg
files = [f for f in os.listdir(FOLDER) if f.lower().endswith(('.jpg', '.jpeg'))]

# Trova la dimensione massima
max_area = 0
target_size = (0, 0)
for f in files:
    with Image.open(os.path.join(FOLDER, f)) as img:
        area = img.width * img.height
        if area > max_area:
            max_area = area
            target_size = (img.width, img.height)

print(f"Dimensione massima trovata: {target_size}")

# Ridimensiona e ritaglia tutte le immagini alla dimensione massima
for f in files:
    path = os.path.join(FOLDER, f)
    with Image.open(path) as img:
        # Calcola il rapporto di ridimensionamento
        ratio_w = target_size[0] / img.width
        ratio_h = target_size[1] / img.height
        ratio = max(ratio_w, ratio_h)
        new_size = (int(img.width * ratio), int(img.height * ratio))
        img_resized = img.resize(new_size, Image.LANCZOS)
        # Ritaglia al centro
        left = (img_resized.width - target_size[0]) // 2
        top = (img_resized.height - target_size[1]) // 2
        right = left + target_size[0]
        bottom = top + target_size[1]
        img_cropped = img_resized.crop((left, top, right, bottom))
        img_cropped.save(path)
        print(f"{f} adattato e ritagliato a {target_size}")