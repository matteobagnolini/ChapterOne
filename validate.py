import requests
import os

LOGIN_URL = "http://localhost/login.php"
LOGOUT_URL = "http://localhost/logout.php"
ADMIN_EMAIL = "admin@example.com"
ADMIN_PASSWORD = "admin123"
CUSTOMER_EMAIL = "anna.bianchi@example.com"
CUSTOMER_PASSWORD = "password123"
SRC_FOLDER = "src"
VALIDATOR_URL = "https://validator.w3.org/nu/?out=text"
base_url = "http://localhost"
pages = []

for file in os.listdir(SRC_FOLDER):
    if file.endswith(".php") and os.path.isfile(os.path.join(SRC_FOLDER, file)):
        url = f"{base_url}/{file}"
        pages.append(url)


report_filename = "validation_report.txt"
with open(report_filename, "w", encoding="utf-8") as report:
    with requests.Session() as session:
    
        login_data = {
            "email": ADMIN_EMAIL,
            "password": ADMIN_PASSWORD,
            "submit": "Login"
        }
        resp = session.post(LOGIN_URL, data=login_data)
        if resp.status_code != 200:
            print("Login fallito!")
            exit(1)
        report.write("Login come admin effettuato con successo.\n")
        for url in pages:
            print(f"Validazione di {url} ...")
            try:
            
                response = session.get(url)
                response.raise_for_status()
                html = response.text

                res = requests.post(
                    VALIDATOR_URL,
                    headers={"Content-Type": "text/html; charset=utf-8"},
                    data=html.encode("utf-8")
                )

                report.write(f"Validazione di {url}:\n")
                report.write(res.text + "\n")
                print(f"Risultato aggiunto a {report_filename}")
            except Exception as e:
                error_msg = f"Errore su {url}: {e}\n"
                report.write(error_msg)
                print(error_msg)

        

        resp = session.post(LOGOUT_URL, data=login_data)
        if resp.status_code != 200:
            print("Logout fallito!")
            exit(1)

        login_data = {
        "email": CUSTOMER_EMAIL,
        "password": CUSTOMER_PASSWORD,
        "submit": "Login"
        }

        resp = session.post(LOGIN_URL, data=login_data)
        if resp.status_code != 200:
            print("Login fallito!")
            exit(1)
        report.write("Login come cliente effettuato con successo.\n")
        for url in pages:
            print(f"Validazione di {url} ...")
            try:
            
                response = session.get(url)
                response.raise_for_status()
                html = response.text

                res = requests.post(
                    VALIDATOR_URL,
                    headers={"Content-Type": "text/html; charset=utf-8"},
                    data=html.encode("utf-8")
                )

                report.write(f"Validazione di {url}:\n")
                report.write(res.text + "\n")
                print(f"Risultato aggiunto a {report_filename}")
            except Exception as e:
                error_msg = f"Errore su {url}: {e}\n"
                report.write(error_msg)
                print(error_msg)

        