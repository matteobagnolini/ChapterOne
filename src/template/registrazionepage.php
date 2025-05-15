<section>
    <h1>Registrazione Utente</h1>
    <form action="processa-registrazione.php" method="POST">
        <fieldset>
            <legend>Informazioni Utente</legend>
            
            <label for="nomeCompleto">Nome Completo:</label>
            <input type="text" id="nomeCompleto" name="nomeCompleto" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="indirizzo">Indirizzo:</label>
            <input type="text" id="indirizzo" name="indirizzo" required>
            
            <label for="telefono">Telefono:</label>
            <input type="tel" id="telefono" name="telefono" required>
        </fieldset>
        
        <button type="submit">Registrati</button>
    </form>
</section>