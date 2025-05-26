<?php
require_once __DIR__ . '/html/db/database.php';

$db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);

try {
    // Eliminazione di tutti i dati esistenti
    $db->db->query("DELETE FROM BOOK_IN_CART");
    $db->db->query("DELETE FROM CART");
    $db->db->query("DELETE FROM REVIEW");
    $db->db->query("DELETE FROM ORDER_DETAIL");
    $db->db->query("DELETE FROM `ORDER`");
    $db->db->query("DELETE FROM DISCOUNT_CODE_USAGE");
    $db->db->query("DELETE FROM DISCOUNT_CODE");
    $db->db->query("DELETE FROM ORDER_NOTIFICATION");
    $db->db->query("DELETE FROM BEST_SELLER");
    $db->db->query("DELETE FROM POST");
    $db->db->query("DELETE FROM BOOK");
    $db->db->query("DELETE FROM CATEGORY");
    $db->db->query("DELETE FROM AUTHOR");
    $db->db->query("DELETE FROM PUBLISHER");
    $db->db->query("DELETE FROM CUSTOMER");
    $db->db->query("DELETE FROM ADMIN");

    // Resetta l'AUTO_INCREMENT per ogni tabella
    $db->db->query("ALTER TABLE BOOK_IN_CART AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE CART AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE REVIEW AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE `ORDER` AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE ORDER_DETAIL AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE DISCOUNT_CODE_USAGE AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE DISCOUNT_CODE AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE ORDER_NOTIFICATION AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE BEST_SELLER AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE POST AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE BOOK AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE CATEGORY AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE AUTHOR AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE PUBLISHER AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE CUSTOMER AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE ADMIN AUTO_INCREMENT = 1");
    
    // Popola la tabella CUSTOMER
    $customerId1 = $db->insertCustomer('Mario', 'Rossi', 'prova@example.com', password_hash('password123', PASSWORD_DEFAULT), 'Via Roma 1', '1234567890');
    $customerId2 = $db->insertCustomer('Luigi', 'Verdi', 'luigi.verdi@example.com', password_hash('password123', PASSWORD_DEFAULT), 'Via Milano 2', '0987654321');
    $customerId3 = $db->insertCustomer('Anna', 'Bianchi', 'anna.bianchi@example.com', password_hash('password123', PASSWORD_DEFAULT), 'Via Napoli 3', '3456789012');

    // Popola la tabella ADMIN
    $adminId = $db->insertAdmin('Admin', 'User', 'admin@example.com', password_hash('admin123', PASSWORD_DEFAULT));

    // Popola la tabella AUTHOR
    $authorId1 = $db->insertAuthor('Umberto', 'Eco');
    $authorId2 = $db->insertAuthor('Italo', 'Calvino');
    $authorId3 = $db->insertAuthor('Agatha', 'Christie');
    $authorId4 = $db->insertAuthor('J.R.R.', 'Tolkien');
    $authorId5 = $db->insertAuthor('Albert', 'Einstein');
    $authorId6 = $db->insertAuthor('Gabriel', 'García Márquez');
    $authorId7 = $db->insertAuthor('Virginia', 'Woolf');
    $authorId8 = $db->insertAuthor('George', 'Orwell');
    $authorId9 = $db->insertAuthor('Haruki', 'Murakami');
    $authorId10 = $db->insertAuthor('walter', 'benjamin');

    // Popola la tabella CATEGORY
    $categoryId1 = $db->insertCategory('Avventura');
    $categoryId2 = $db->insertCategory('Fantascienza');
    $categoryId3 = $db->insertCategory('Giallo');
    $categoryId4 = $db->insertCategory('Fantasy');
    $categoryId5 = $db->insertCategory('Storia');
    $categoryId6 = $db->insertCategory('Storico');
    $categoryId7 = $db->insertCategory('Saggistica');
    $categoryId8 = $db->insertCategory('Modernista');
    $categoryId9 = $db->insertCategory('Poesia');
    $categoryId10 = $db->insertCategory('Biografico');
    $categoryId11 = $db->insertCategory('Letteratura');
    $categoryId12 = $db->insertCategory('Scientifico');


    // Popola la tabella PUBLISHER
    $publisherId1 = $db->insertPublisher('Mondadori', 'Via della Libertà 10');
    $publisherId2 = $db->insertPublisher('Feltrinelli', 'Corso Italia 20');
    $publisherId3 = $db->insertPublisher('Einaudi', 'Piazza della Repubblica 30');
    $publisherId4 = $db->insertPublisher('Rizzoli', 'Via Roma 40');
    $publisherId5 = $db->insertPublisher('Bompiani', 'Via Garibaldi 50');
    $publisherId6 = $db->insertPublisher('Garzanti', 'Corso Buenos Aires 60');

    // Popola la tabella BOOK con 9 libri
    $bookId1 = $db->insertBookWithExceptr(
        'Quale verità?',
        'Un romanzo epico che racconta la storia di una famiglia attraverso tre generazioni. Le vicende si intrecciano tra amori, tradimenti e segreti nascosti, offrendo uno spaccato intenso della società italiana del Novecento. Un viaggio emozionante tra passato e presente, dove ogni personaggio lascia un segno indelebile.',
        19.99, 'images/quale_verita.jpg', 'exceptr/text.txt', $categoryId1, $publisherId1, $authorId1
    );
    $bookId2 = $db->insertBookWithExceptr(
        'Viaggio nello Spazio',
        'Un racconto di fantascienza ambientato nel 2150, dove l\'umanità ha colonizzato nuovi pianeti. Il protagonista affronta sfide tecnologiche e morali, esplorando i limiti della conoscenza e della sopravvivenza. Un\'avventura che mette in discussione il futuro della specie umana.',
        15.99, 'images/shining.jpg', 'exceptr/text1.txt', $categoryId2, $publisherId2, $authorId2
    );
    $bookId3 = $db->insertBookWithExceptr(
        'Il Mistero del Lago',
        'Un giallo avvincente ambientato in un piccolo villaggio di montagna, dove un misterioso omicidio sconvolge la quiete locale. L\'investigatore incaricato dovrà scavare tra antichi rancori e segreti di famiglia per arrivare alla verità. Suspense e colpi di scena fino all\'ultima pagina.',
        18.50, 'images/il_mistero_del_lago.jpg', 'exceptr/text2.txt', $categoryId3, $publisherId3, $authorId3
    );
    $bookId4 = $db->insertBookWithExceptr(
        'La Terra di Mezzo',
        'Un fantasy epico con draghi, elfi e antiche magie. Il giovane eroe si trova coinvolto in una guerra millenaria tra il bene e il male, attraversando terre incantate e affrontando creature leggendarie. Un racconto di coraggio, amicizia e destino.',
        22.00, 'images/terra_mezzo.jpg', '', $categoryId4, $publisherId1, $authorId4
    );
    $bookId5 = $db->insertBookWithExceptr(
        'Vita di Einstein',
        'La biografia del famoso scienziato raccontata attraverso episodi chiave della sua vita. Dall\'infanzia curiosa alle rivoluzionarie scoperte scientifiche, il libro offre uno sguardo umano e profondo su uno dei più grandi geni della storia. Un viaggio tra scienza, filosofia e passioni personali.',
        24.99, 'images/einst.jpg', 'exceptr/text4.txt', $categoryId5, $publisherId4, $authorId5
    );
    $bookId6 = $db->insertBookWithExceptr(
        'Steve jobs',
        'La biografia di Steve Jobs, il visionario fondatore di Apple. Attraverso aneddoti e testimonianze, il libro racconta la sua vita, le sue sfide e le sue conquiste, offrendo un ritratto intimo e ispiratore di un uomo che ha cambiato il mondo della tecnologia. Un viaggio tra creatività, innovazione e leadership.',	
        21.50, 'images/stevejobs.jpg', 'exceptr/text5.txt', $categoryId1, $publisherId1, $authorId10
    );
    $bookId7 = $db->insertBookWithExceptr(
        'Il barone rampante',
        'Un viaggio tra dimensioni alternative dove ogni scelta crea una nuova realtà. Il protagonista si trova a dover affrontare versioni diverse di sé stesso, esplorando i confini tra sogno e realtà. Un romanzo che invita a riflettere sul destino e sulle infinite possibilità della vita.',
        17.99, 'images/il_barone_rampante.jpg', 'exceptr/text6.txt', $categoryId2, $publisherId2, $authorId2
    );
    $bookId8 = $db->insertBookWithExceptr(
        'Il Codice Segreto di Leonardo',
        'Un mistero da risolvere in una corsa contro il tempo. Un antico manoscritto scomparso, enigmi da decifrare e una società segreta pronta a tutto pur di proteggere i propri segreti. Un thriller che tiene il lettore con il fiato sospeso fino all\'ultima pagina.',
        16.50, 'images/il_codice_segreto_di_leonardo.jpg', 'exceptr/text7.txt', $categoryId3, $publisherId3, $authorId3
    );
    $bookId9 = $db->insertBookWithExceptr(
        'Le Cronache del Regno',
        'Una saga fantasy di avventura e magia, dove il destino di un intero regno è nelle mani di un gruppo di eroi improbabili. Tra battaglie epiche, tradimenti e antiche profezie, i protagonisti dovranno trovare la forza di cambiare il proprio destino.',
        23.99, 'images/il_signore_degli_anelli.jpg', 'exceptr/text8.txt', $categoryId4, $publisherId4, $authorId4
    );
    $bookId10 = $db->insertBookWithExceptr(
        'Il Pendolo di Foucault',
        'Un thriller storico e filosofico tra misteri e simboli. Tre redattori editoriali si immergono in una caccia al tesoro intellettuale che li porterà a confrontarsi con antiche società segrete, enigmi cabalistici e la sottile linea tra realtà e fantasia. Un romanzo che sfida la mente e la percezione.',
        20.99, 'images/pendolo_foucault.jpg', 'exceptr/eco1.txt', $categoryId6, $publisherId1, $authorId1
    );
    $bookId11 = $db->insertBookWithExceptr(
        'Se una notte d\'inverno un viaggiatore',
        'Un romanzo sperimentale sulla lettura e la scrittura, dove il lettore diventa protagonista di una storia fatta di inizi interrotti e misteri irrisolti. Un viaggio meta-letterario che esplora il piacere e la frustrazione della narrazione. Un omaggio all\'immaginazione e al potere dei libri.',
        18.99, 'images/notte_inverno.jpg', 'exceptr/calvino1.txt', $categoryId11, $publisherId2, $authorId2
    );
    $bookId12 = $db->insertBookWithExceptr(
        'Dieci piccoli indiani',
        'Un classico giallo ricco di suspense. Dieci sconosciuti vengono invitati su un\'isola misteriosa e, uno dopo l\'altro, cadono vittime di un assassino invisibile. Un intreccio perfetto di tensione, indizi e colpi di scena che ha fatto la storia del genere.',
        17.50, 'images/dieci_piccoli.jpg', 'exceptr/christie1.txt', $categoryId3, $publisherId3, $authorId3
    );
    $bookId13 = $db->insertBookWithExceptr(
        'Cent\'anni di solitudine',
        'La saga della famiglia Buendía in un realismo magico che attraversa generazioni. Amori, guerre, sogni e follie si intrecciano nel villaggio di Macondo, in un racconto che fonde storia e leggenda. Un capolavoro della letteratura mondiale, ricco di simbolismi e poesia.',
        21.99, 'images/centanni.jpg', 'exceptr/marquez1.txt', $categoryId11, $publisherId5, $authorId6
    );
    $bookId14 = $db->insertBookWithExceptr(
        'Mrs Dalloway',
        'Un giorno nella vita di Clarissa Dalloway nella Londra degli anni Venti. Attraverso i suoi pensieri e quelli delle persone che la circondano, il romanzo esplora temi come il tempo, la memoria e l\'identità. Un\'opera intensa e raffinata, simbolo della modernità letteraria.',
        16.99, 'images/Mrs_Dalloway.jpg', 'exceptr/woolf1.txt', $categoryId8, $publisherId6, $authorId7
    );
    $bookId15 = $db->insertBookWithExceptr(
        '1984',
        'Un romanzo distopico su una società totalitaria dove ogni aspetto della vita è controllato dal Grande Fratello. Il protagonista, Winston Smith, cerca la libertà in un mondo di sorveglianza e repressione. Un monito potente contro la perdita della libertà e dell\'individualità.',
        19.50, 'images/1984.jpg', 'exceptr/orwell1.txt', $categoryId2, $publisherId2, $authorId8
    );
    $bookId16 = $db->insertBookWithExceptr(
        'Kafka sulla spiaggia',
        'Un viaggio surreale tra sogno e realtà, dove due storie si intrecciano tra misteri, simboli e magia. Il giovane Kafka Tamura fugge di casa e si trova coinvolto in eventi inspiegabili, mentre un anziano dotato di poteri straordinari cerca il proprio destino. Un romanzo visionario e poetico.',
        22.50, 'images/Kafka_sulla_spiaggia.jpg', 'exceptr/murakami1.txt', $categoryId11, $publisherId4, $authorId9
    );
    $bookId17 = $db->insertBookWithExceptr(
        'Il Signore degli Anelli',
        'La grande epopea fantasy della Terra di Mezzo. Frodo e i suoi compagni affrontano un viaggio per distruggere l\'Anello del Potere, sfidando forze oscure e vivendo avventure indimenticabili. Un capolavoro di immaginazione, amicizia e coraggio.',
        25.99, 'images/tlor.jpg', 'exceptr/tolkien1.txt', $categoryId4, $publisherId1, $authorId4
    );
    $bookId18 = $db->insertBookWithExceptr(
        'Come vedo il mondo',
        'Riflessioni e saggi di Albert Einstein su scienza, filosofia, politica e società. Un viaggio nel pensiero di uno dei più grandi geni del Novecento, tra curiosità, umanità e impegno civile. Un libro che invita a guardare il mondo con occhi nuovi.',
        18.00, 'images/come_vedo_mondo.jpg', 'exceptr/einstein1.txt', $categoryId12, $publisherId5, $authorId5
    );
    $bookId19 = $db->insertBookWithExceptr(
        'Baudolino',
        'Un romanzo storico e avventuroso di Umberto Eco che segue le vicende di Baudolino, un giovane astuto e sognatore nel Medioevo. Tra intrighi di corte, viaggi fantastici e incontri con personaggi leggendari, la narrazione si snoda tra realtà e invenzione, esplorando il potere delle storie e della menzogna. Un viaggio ironico e colto nell\'Europa medievale.',
        18.90, 'images/baudolino.jpg', 'exceptr/eco2.txt', $categoryId6, $publisherId1, $authorId1
    );
    $bookId20 = $db->insertBookWithExceptr(
        'Le città invisibili',
        'Italo Calvino ci conduce in un viaggio immaginario attraverso città fantastiche descritte da Marco Polo all\'imperatore dei Tartari. Ogni città è una metafora, un sogno, una riflessione sulla memoria, il desiderio e il tempo. Un\'opera poetica e visionaria che invita il lettore a esplorare i confini tra realtà e immaginazione.',
        17.50, 'images/citta_invisibili.jpg', 'exceptr/calvino2.txt', $categoryId11, $publisherId2, $authorId2
    );
    $bookId21 = $db->insertBookWithExceptr(
        'Assassinio sull\'Orient Express',
        'Uno dei più celebri romanzi di Agatha Christie, in cui il detective Hercule Poirot si trova a risolvere un omicidio avvenuto a bordo del lussuoso treno Orient Express. Tra passeggeri sospetti e alibi intricati, la tensione cresce fino a un finale sorprendente. Un classico del giallo, ricco di suspense e colpi di scena.',
        16.99, 'images/orient_express.jpg', 'exceptr/christie2.txt', $categoryId3, $publisherId3, $authorId3
    );
    $bookId22 = $db->insertBookWithExceptr(
        'Lo Hobbit',
        'Prequel de Il Signore degli Anelli, questo romanzo di J.R.R. Tolkien narra le avventure di Bilbo Baggins, un hobbit riluttante che si unisce a un gruppo di nani per recuperare un tesoro custodito da un drago. Tra incontri con creature magiche, enigmi e battaglie, la storia celebra il coraggio e la crescita personale.',
        19.99, 'images/hobbit.jpg', 'exceptr/tolkien2.txt', $categoryId4, $publisherId1, $authorId4
    );
    $bookId23 = $db->insertBookWithExceptr(
        'L\'universo come lo vedo io',
        'In questa raccolta di saggi, Albert Einstein affronta temi di scienza, filosofia e società con chiarezza e profondità. Le sue riflessioni spaziano dalla relatività alla pace mondiale, offrendo uno sguardo unico sul pensiero di uno dei più grandi scienziati della storia. Un libro che stimola la mente e il cuore.',
        15.99, 'images/einstein2.jpg', 'exceptr/einstein2.txt', $categoryId12, $publisherId5, $authorId5
    );
    $bookId24 = $db->insertBookWithExceptr(
        'L\'autunno del patriarca',
        'Gabriel García Márquez racconta la solitudine e la decadenza di un dittatore sudamericano in un romanzo denso di immagini poetiche e simbolismi. La narrazione fluisce come un lungo monologo, esplorando il potere, la corruzione e la memoria collettiva. Un capolavoro del realismo magico e della letteratura mondiale.',
        20.50, 'images/autunno_patriarca.jpg', 'exceptr/marquez2.txt', $categoryId11, $publisherId5, $authorId6
    );
    $bookId25 = $db->insertBookWithExceptr(
        'Gita al faro',
        'Virginia Woolf ci offre un intenso ritratto di una famiglia inglese e dei loro ospiti durante una vacanza sull\'isola di Skye. Attraverso flussi di coscienza e introspezioni profonde, il romanzo esplora il tempo, la memoria e le relazioni umane. Un\'opera raffinata e innovativa che ha segnato la letteratura del Novecento.',
        17.80, 'images/gita_faro.jpg', 'exceptr/woolf2.txt', $categoryId8, $publisherId6, $authorId7
    );
    $bookId26 = $db->insertBookWithExceptr(
        'La fattoria degli animali',
        'George Orwell scrive una favola satirica che racconta la ribellione degli animali di una fattoria contro i loro padroni umani. La rivoluzione, però, si trasforma presto in una nuova forma di oppressione. Un\'allegoria potente e attuale sui pericoli del totalitarismo e della manipolazione politica.',
        15.50, 'images/fattoria.jpg', 'exceptr/orwell2.txt', $categoryId2, $publisherId2, $authorId8
    );
    $bookId27 = $db->insertBookWithExceptr(
        'Norwegian Wood',
        'Haruki Murakami ci trasporta nel Giappone degli anni Sessanta, tra amore, perdita e crescita personale. Il protagonista, Toru, si trova diviso tra due donne molto diverse, mentre affronta il dolore e la ricerca di sé. Un romanzo delicato e malinconico, ricco di atmosfere suggestive e riflessioni sulla vita.',
        18.20, 'images/norw_food.jpg', 'exceptr/murakami2.txt', $categoryId11, $publisherId4, $authorId9
    );
    $bookId28 = $db->insertBookWithExceptr(
        'Il nome della rosa',
        'Umberto Eco ci trasporta in un monastero medievale dove un frate e il suo giovane assistente indagano su una serie di misteriosi omicidi. Tra manoscritti proibiti, intrighi religiosi e una biblioteca labirintica, il romanzo fonde giallo, storia e filosofia in un capolavoro avvincente e ricco di suspense.',
        21.00, 'images/nome_rosa.jpg', 'exceptr/eco3.txt', $categoryId6, $publisherId1, $authorId1
    );
    $bookId29 = $db->insertBookWithExceptr(
        'Il barone rampante',
        'Italo Calvino racconta la storia di Cosimo Piovasco di Rondò, che decide di vivere sugli alberi senza mai scendere a terra. Attraverso le sue avventure tra i rami, il romanzo esplora la libertà, la fantasia e il rapporto con la società, in un racconto poetico e originale.',
        16.90, 'images/barone_rampante.jpg', 'exceptr/calvino3.txt', $categoryId11, $publisherId2, $authorId2
    );
    $bookId30 = $db->insertBookWithExceptr(
        'Poirot a Styles Court',
        'Nel primo romanzo che vede protagonista Hercule Poirot, Agatha Christie ci conduce in una villa inglese dove viene commesso un omicidio apparentemente insolubile. Con il suo ingegno e la sua logica, Poirot svela i segreti nascosti dietro le apparenze. Un classico intramontabile del giallo.',
        15.50, 'images/poirot.jpg', 'exceptr/christie3.txt', $categoryId3, $publisherId3, $authorId3
    );
    $bookId31 = $db->insertBookWithExceptr(
        'I figli di Húrin',
        'J.R.R. Tolkien ci narra una delle storie più tragiche della Terra di Mezzo: la vicenda di Túrin Turambar e della sua famiglia, segnata dalla maledizione di Morgoth. Un racconto epico di eroismo, destino e dolore, arricchito da creature leggendarie e paesaggi incantati.',
        22.50, 'images/hurin.jpg', 'exceptr/tolkien3.txt', $categoryId4, $publisherId1, $authorId4
    );
    $bookId32 = $db->insertBookWithExceptr(
        'Pensieri, idee, opinioni',
        'Una raccolta di scritti di Albert Einstein che spaziano dalla scienza alla filosofia, dalla religione alla politica. Attraverso riflessioni profonde e accessibili, il grande fisico ci offre una visione umanistica e universale del mondo, invitando alla curiosità e al dialogo.',
        17.00, 'images/pensieri.jpg', 'exceptr/einstein3.txt', $categoryId12, $publisherId5, $authorId5
    );
    $bookId33 = $db->insertBookWithExceptr(
        'Cronaca di una morte annunciata',
        'Gabriel García Márquez racconta la storia di un delitto annunciato in un piccolo paese sudamericano. Attraverso una narrazione circolare e corale, il romanzo esplora il destino, l\'onore e l\'indifferenza collettiva, in un intreccio di realismo magico e denuncia sociale.',
        16.80, 'images/cronaca.jpg', 'exceptr/marquez3.txt', $categoryId11, $publisherId5, $authorId6
    );
    $bookId34 = $db->insertBookWithExceptr(
        'Orlando',
        'Virginia Woolf ci regala la storia di Orlando, un personaggio che attraversa i secoli cambiando sesso e vivendo molte vite. Un romanzo innovativo e visionario che riflette sull\'identità, il tempo e la trasformazione, con uno stile poetico e ironico.',
        18.40, 'images/orlando.jpg', 'exceptr/woolf3.txt', $categoryId8, $publisherId6, $authorId7
    );
    $bookId35 = $db->insertBookWithExceptr(
        'Omaggio alla Catalogna',
        'George Orwell racconta la sua esperienza nella guerra civile spagnola, offrendo una testimonianza diretta e appassionata dei conflitti politici e umani. Un libro che unisce reportage, riflessione storica e denuncia delle manipolazioni ideologiche.',
        16.70, 'images/omaggio.jpg', 'exceptr/orwell3.txt', $categoryId6, $publisherId2, $authorId8
    );
    $bookId36 = $db->insertBookWithExceptr(
        'L\'uomo che inseguiva le nuvole',
        'Haruki Murakami ci conduce in un viaggio onirico e surreale, dove il protagonista si trova a esplorare i confini tra sogno e realtà, memoria e desiderio. Un romanzo ricco di simbolismi, atmosfere sospese e riflessioni sulla solitudine e la ricerca di senso.',
        19.10, 'images/uomo_nuvole.jpg', 'exceptr/murakami3.txt', $categoryId11, $publisherId4, $authorId9
    );

    // Popola la tabella CART (i carrelli vengono creati automaticamente dal trigger after_insert_customer)
    $cart1 = $db->getCartByCustomerId($customerId1);
    $cart2 = $db->getCartByCustomerId($customerId2);
    $cart3 = $db->getCartByCustomerId($customerId3);

    // Popola la tabella BOOK_IN_CART
    $db->insertBookInCart($cart1['Id'], $bookId1, 1);
    $db->insertBookInCart($cart1['Id'], $bookId4, 2);
    $db->insertBookInCart($cart2['Id'], $bookId2, 1);
    $db->insertBookInCart($cart2['Id'], $bookId5, 1);
    $db->insertBookInCart($cart3['Id'], $bookId3, 3);

    

    // Popola la tabella DISCOUNT_CODE
    $discountCodeId1 = $db->insertDiscountCode('WELCOME10', 'percentage', 10.00, '2024-01-01', '2025-12-31', false, true);
    $discountCodeId2 = $db->insertDiscountCode('SUMMER25', 'percentage', 25.00, '2025-06-01', '2025-08-31', false, true);
    $discountCodeId3 = $db->insertDiscountCode('FIXED15', 'fixed', 15.00, '2024-01-01', '2025-12-31', false, true);
    

    // --- ORDINI E DETTAGLI ORDINE ---

    // Ordine 1: Mario Rossi compra Il Grande Romanzo, La Terra di Mezzo
    $db->insertBookInCart($cart1['Id'], $bookId1, 1);
    $db->insertBookInCart($cart1['Id'], $bookId4, 1);
    $orderId1 = $db->insertOrder('2025-03-15 10:30:00', 41.99, $customerId1, null);
    $db->insertOrderDetail(1, 19.99, $orderId1, $bookId1);
    $db->insertOrderDetail(1, 22.00, $orderId1, $bookId4);

    // Ordine 2: Luigi Verdi compra Viaggio nello Spazio, Vita di Einstein
    $db->insertBookInCart($cart2['Id'], $bookId2, 1);
    $db->insertBookInCart($cart2['Id'], $bookId5, 1);
    $orderId2 = $db->insertOrder('2025-03-20 14:45:00', 40.98, $customerId2, $discountCodeId1);
    $db->insertOrderDetail(1, 15.99, $orderId2, $bookId2);
    $db->insertOrderDetail(1, 24.99, $orderId2, $bookId5);

    // Ordine 3: Anna Bianchi compra Il Mistero del Lago, Dieci piccoli indiani, Cent'anni di solitudine
    $db->insertBookInCart($cart3['Id'], $bookId3, 1);
    $db->insertBookInCart($cart3['Id'], $bookId12, 1);
    $db->insertBookInCart($cart3['Id'], $bookId13, 1);
    $orderId3 = $db->insertOrder('2025-03-25 09:15:00', 57.99, $customerId3, null);
    $db->insertOrderDetail(1, 18.50, $orderId3, $bookId3);
    $db->insertOrderDetail(1, 17.50, $orderId3, $bookId12);
    $db->insertOrderDetail(1, 21.99, $orderId3, $bookId13);

    // Ordine 4: Mario Rossi compra Mrs Dalloway, Il Signore degli Anelli
    $db->insertBookInCart($cart1['Id'], $bookId14, 1);
    $db->insertBookInCart($cart1['Id'], $bookId17, 1);
    $orderId4 = $db->insertOrder('2025-04-01 11:00:00', 42.98, $customerId1, null);
    $db->insertOrderDetail(1, 16.99, $orderId4, $bookId14);
    $db->insertOrderDetail(1, 25.99, $orderId4, $bookId17);

    // Ordine 5: Luigi Verdi compra 1984, Saggi di Einstein
    $db->insertBookInCart($cart2['Id'], $bookId15, 1);
    $db->insertBookInCart($cart2['Id'], $bookId18, 1);
    $orderId5 = $db->insertOrder('2025-04-05 16:20:00', 23.50, $customerId2, null);
    $db->insertOrderDetail(1, 19.50, $orderId5, $bookId15);
    $db->insertOrderDetail(1, 4.00, $orderId5, $bookId18);

    // Ordine 6: Anna Bianchi compra Kafka sulla spiaggia, Orlando
    $db->insertBookInCart($cart3['Id'], $bookId16, 1);
    $db->insertBookInCart($cart3['Id'], $bookId34, 1);
    $orderId6 = $db->insertOrder('2025-04-10 09:00:00', 40.90, $customerId3, null);
    $db->insertOrderDetail(1, 22.50, $orderId6, $bookId16);
    $db->insertOrderDetail(1, 18.40, $orderId6, $bookId34);

    // Ordine 7: Mario Rossi compra Baudolino, Le città invisibili
    $db->insertBookInCart($cart1['Id'], $bookId19, 1);
    $db->insertBookInCart($cart1['Id'], $bookId20, 1);
    $orderId7 = $db->insertOrder('2025-04-15 13:30:00', 36.40, $customerId1, null);
    $db->insertOrderDetail(1, 18.90, $orderId7, $bookId19);
    $db->insertOrderDetail(1, 17.50, $orderId7, $bookId20);

    // Ordine 8: Luigi Verdi compra Assassinio sull'Orient Express, Lo Hobbit
    $db->insertBookInCart($cart2['Id'], $bookId21, 1);
    $db->insertBookInCart($cart2['Id'], $bookId22, 1);
    $orderId8 = $db->insertOrder('2025-04-20 18:10:00', 36.98, $customerId2, null);
    $db->insertOrderDetail(1, 16.99, $orderId8, $bookId21);
    $db->insertOrderDetail(1, 19.99, $orderId8, $bookId22);

    // Ordine 9: Anna Bianchi compra L'autunno del patriarca, Gita al faro
    $db->insertBookInCart($cart3['Id'], $bookId24, 1);
    $db->insertBookInCart($cart3['Id'], $bookId25, 1);
    $orderId9 = $db->insertOrder('2025-04-25 12:00:00', 38.30, $customerId3, null);
    $db->insertOrderDetail(1, 20.50, $orderId9, $bookId24);
    $db->insertOrderDetail(1, 17.80, $orderId9, $bookId25);

    // Ordine 10: Mario Rossi compra La fattoria degli animali, Omaggio alla Catalogna
    $db->insertBookInCart($cart1['Id'], $bookId26, 1);
    $db->insertBookInCart($cart1['Id'], $bookId35, 1);
    $orderId10 = $db->insertOrder('2025-05-01 15:00:00', 32.20, $customerId1, null);
    $db->insertOrderDetail(1, 15.50, $orderId10, $bookId26);
    $db->insertOrderDetail(1, 16.70, $orderId10, $bookId35);

    // Ordine 11: Luigi Verdi compra Norwegian Wood, Cronaca di una morte annunciata
    $db->insertBookInCart($cart2['Id'], $bookId27, 1);
    $db->insertBookInCart($cart2['Id'], $bookId33, 1);
    $orderId11 = $db->insertOrder('2025-05-05 17:30:00', 35.00, $customerId2, null);
    $db->insertOrderDetail(1, 18.20, $orderId11, $bookId27);
    $db->insertOrderDetail(1, 16.80, $orderId11, $bookId33);

    // Ordine 12: Anna Bianchi compra L'uomo che inseguiva le nuvole, Il barone rampante
    $db->insertBookInCart($cart3['Id'], $bookId36, 1);
    $db->insertBookInCart($cart3['Id'], $bookId29, 1);
    $orderId12 = $db->insertOrder('2025-05-10 10:30:00', 36.00, $customerId3, null);
    $db->insertOrderDetail(1, 19.10, $orderId12, $bookId36);
    $db->insertOrderDetail(1, 16.90, $orderId12, $bookId29);

    // Ordine 13: Mario Rossi compra Il nome della rosa, I figli di Húrin
    $db->insertBookInCart($cart1['Id'], $bookId28, 1);
    $db->insertBookInCart($cart1['Id'], $bookId31, 1);
    $orderId13 = $db->insertOrder('2025-05-15 14:00:00', 43.50, $customerId1, null);
    $db->insertOrderDetail(1, 21.00, $orderId13, $bookId28);
    $db->insertOrderDetail(1, 22.50, $orderId13, $bookId31);

    // Ordine 14: Luigi Verdi compra Poirot a Styles Court, Pensieri, idee, opinioni
    $db->insertBookInCart($cart2['Id'], $bookId30, 1);
    $db->insertBookInCart($cart2['Id'], $bookId32, 1);
    $orderId14 = $db->insertOrder('2025-05-20 16:45:00', 32.50, $customerId2, null);
    $db->insertOrderDetail(1, 15.50, $orderId14, $bookId30);
    $db->insertOrderDetail(1, 17.00, $orderId14, $bookId32);

    // Ordine 15: Anna Bianchi compra L'universo come lo vedo io
    $db->insertBookInCart($cart3['Id'], $bookId23, 1);
    $orderId15 = $db->insertOrder('2025-05-25 11:15:00', 15.99, $customerId3, null);
    $db->insertOrderDetail(1, 15.99, $orderId15, $bookId23);

    // --- FINE ORDINI ---
    

    // Popola la tabella REVIEW
    $db->insertReview('Libro fantastico, lo consiglio vivamente! La scrittura è coinvolgente e la storia mi ha emozionato.', 5, $bookId1, $customerId1);
    $db->insertReview('Un fantasy epico, mi ha ricordato le grandi saghe. Consigliato!', 4, $bookId4, $customerId1);
    $db->insertReview('Storia avvincente e ben scritta, mi ha tenuto incollato fino alla fine.', 5, $bookId2, $customerId2);
    $db->insertReview('Interessante e ben documentato, perfetto per chi ama la scienza.', 4, $bookId5, $customerId2);
    $db->insertReview('Giallo intrigante, colpi di scena fino all\'ultima pagina.', 5, $bookId3, $customerId3);
    $db->insertReview('Un classico del giallo, trama avvincente e finale sorprendente.', 5, $bookId12, $customerId3);
    $db->insertReview('Capolavoro assoluto, ogni pagina è un viaggio.', 5, $bookId17, $customerId1);
    $db->insertReview('Romanzo potente e attuale, fa riflettere.', 5, $bookId15, $customerId2);
    $db->insertReview('Surreale e poetico, Murakami non delude mai.', 4, $bookId16, $customerId3);
    $db->insertReview('Intrigante e ricco di mistero, consigliato agli amanti del genere.', 5, $bookId28, $customerId1);
    $db->insertReview('Romanzo delicato e malinconico, emozionante.', 4, $bookId27, $customerId2);
    $db->insertReview('Originale e divertente, Calvino è sempre una garanzia.', 5, $bookId29, $customerId3);
    
    
    // Popola la tabella ORDER_NOTIFICATION
    $db->insertOrderNotification($orderId1,'Ordine spedito','Il tuo ordine è stato spedito', 'sent');
    $db->insertOrderNotification($orderId2,'Ordine in elaborazione', 'Il tuo ordine è in elaborazione', 'pending');
    $db->insertOrderNotification($orderId3,'Ordine consegnato', 'Il tuo ordine è stato consegnato', 'arrived');
    $db->insertOrderNotification($orderId4,'Ordine in preparazione','Il tuo ordine è in preparazione', 'pending');
    $db->insertOrderNotification($orderId5,'Ordine spedito','Il tuo ordine è stato spedito', 'sent');
    $db->insertOrderNotification($orderId7,'Ordine consegnato','Il tuo ordine è stato consegnato', 'arrived');
    $db->insertOrderNotification($orderId8,'Ordine in attesa di pagamento','Attendi la conferma del pagamento', 'pending');
    $db->insertOrderNotification($orderId9,'Ordine spedito','Il tuo ordine è stato spedito', 'sent');
    $db->insertOrderNotification($orderId11,'Ordine in preparazione','Il tuo ordine è in preparazione', 'pending');
    $db->insertOrderNotification($orderId12,'Ordine spedito','Il tuo ordine è stato spedito', 'sent');
    $db->insertOrderNotification($orderId13,'Ordine consegnato','Il tuo ordine è stato consegnato', 'arrived');


    // Popola la tabella BOOK_IN_CART
    $db->insertBookInCart($cart1['Id'], $bookId1, 1);
    $db->insertBookInCart($cart1['Id'], $bookId4, 2);
    $db->insertBookInCart($cart2['Id'], $bookId2, 1);
    $db->insertBookInCart($cart2['Id'], $bookId5, 1);
    $db->insertBookInCart($cart3['Id'], $bookId3, 3);

    echo "Database popolato con successo!";
} catch (Exception $e) {
    echo "Errore durante il popolamento del database: " . $e->getMessage();
}
?>