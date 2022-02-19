DROP TABLE IF EXISTS `WISHLIST`;
DROP TABLE IF EXISTS `IMAGE`;
DROP TABLE IF EXISTS `PRODUCT`;
DROP TABLE IF EXISTS `USER`;

CREATE TABLE `PRODUCT` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(64)  NOT NULL,
  `cat` char(1)  NOT NULL,
  `descrizione` varchar(5000)  NOT NULL,
  `banner` varchar(256)  NOT NULL,
  `banner_alt` varchar(32)  NOT NULL,
  `thumb` varchar(256)  NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `IMAGE` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(256)  NOT NULL,
  `alt` varchar(32)  NOT NULL,
  `prod` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prod` (`prod`),
  FOREIGN KEY (`prod`) REFERENCES `PRODUCT` (`id`)
);

CREATE TABLE `USER` (
  `email` varchar(256)  NOT NULL,
  `password` char(64)  NOT NULL,
  PRIMARY KEY (`email`)
);

CREATE TABLE `WISHLIST` (
 `user` varchar(256) NOT NULL , 
 `prod` int NOT NULL,
  PRIMARY KEY (`user`, `prod`),
  FOREIGN KEY (`user`) REFERENCES `USER` (`email`),
  FOREIGN KEY (`prod`) REFERENCES `PRODUCT` (`id`)
); 
 
INSERT INTO `USER` (`email`, `password`) VALUES ('admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918'), ('user', '04f8996da763b7a969b1028ee3007569eaf3a635486ddab211d512c85b9df8fb');

INSERT INTO `PRODUCT` (`id`, `nome`, `cat`, `descrizione`, `banner`, `banner_alt`, `thumb`) VALUES
(1, 'nora', '1', 'Cucina nora. Oggi lo stile classico è di tendenza. Molto di tendenza! Ante e frontali in legno massiccio di color panna, una sfumatura tenue e particolarmente indicata per un ambiente di elegante memoria <span lang=\"en\">vintage</span>. Colonnine sagomate di grande effetto decorativo, adatte a definire elementi e volumi.', '../uploads/3vgoY4Uhea.jpg', 'luminosa cucina classica', '../uploads/E0wvrK2yPu.jpg'),
(2, 'atlante a128', '2', '<span lang=\"en\">Tetris</span>: l&#039;elemento a giorno retroilluminato in metallo è un elemento di raccordo tra i pensili e ha la schiena personalizzabile. L&#039;arredo <span lang=\"en\">Panka</span> ad angolo valorizza la parete laterale diventando una base d&#039;appoggio che non appesantisce lo spazio.', '../uploads/6i7XlFuPZh.jpg', 'luminoso soggiorno moderno', '../uploads/IYGXMmJUtT.jpg'),
(3, 'anta <span lang=\"en\">vision</span>', '3', 'L&#039;anta <span lang=\"en\">Vision</span> unisce design e funzionalità. I fili non si vedono, grazie alla soluzione integrata nell&#039;anta con meccanismo di trascinamento cavi, progettato per scorrere in sicurezza anche quando lo schermo è acceso. Tutto è già predisposto per voi, compresi i cavi antenna satellite e soprattutto &quot;nascosto&quot;, garantendo la massima pulizia delle linee. Il cablaggio è stato configurato per un&#039;ottimale possibilità di intervento sul televisore, la migliore di tutta la categoria. Massima libertà di scelta per la vostra Televisore:  fino a 32 pollici per il modulo da 98, fino a 40 pollici per il modulo da 117,2,  fino a 50 pollici per il modulo da 136,2 il modulo <span lang=\"en\">Vision</span> permette la regolazione in altezza e profondità per il posizionamento del televisore fino a 75 millimetri di spessore.', '../uploads/MsDojJ9pUl.jpg', 'armadio a specchio in una camera', '../uploads/zba8KrER2Q.jpg'),
(4, '<span lang=\"en\">theo</span>', '5', 'Le finiture e l&#039;attenzione ai dettagli lo rendono un prodotto accattivante e dalla forte personalità. <span lang=\"en\">Theo</span> risulta essere, quindi, un modello rassicurante per linee e <span lang=\"en\">comfort</span> e allo stesso tempo grintoso e &quot;fuori dagli schemi&quot;. La sua morbidezza contrasta con la linea grafica della cucitura che definisce le forme del divano e lo rende un modello prezioso e unico. L&#039;elevato <span lang=\"en\">comfort</span> è dato dall&#039;imbottitura del cuscino di seduta e schienale in poliuretano espanso, piuma d&#039;oca e fibra di poliestere e infine dal molleggio a cinghie elastiche. In gamma vi sono elementi con due braccioli, componibili - divani, poltrone e meridiana - e una gradevole poltroncina con braccioli più stretti rispetto ai divani. Gli elementi sono disponibili con rete elettrosaldata e materasso in poliuretano espanso ad alta densità. È presente anche con bracciolo stretto. Il modello in pelle, inoltre, è disponibile anche nella versione senza gonna con bracciolo standard o stretto.', '../uploads/PoDlwCaJNr.jpg', 'divano in un ampio soggiorno', '../uploads/swRVIrOpTd.jpg'),
(5, 'bro', '5', 'Bro presenta un telaio in massello di legno e multistrato di abete. Il rivestimento è in tessuto completamente sfoderabile con cucitura &quot;pizzicata&quot; oppure in pelle cucito con &quot;impuntura&quot; a struttura fissa e cuscini sfoderabili. La struttura è imbottita con inserti sagomati in poliuretano espanso con densità differenziate ed infine ricoperta con una fodera in falda resinata accoppiata a maglina. I cuscini di seduta sono in piuma d&#039;oca lavata e sterilizzata e fiocchi di fibra di poliestere suddivisi a scomparti con anima in poliuretano espanso. I cuscini dello schienale sono in piuma d&#039;oca lavata e sterilizzata e fiocchi di fibra di poliestere suddivisi a scomparti.', '../uploads/e9gC7pxsyd.png', 'divani in salotto luminoso', '../uploads/7gJkXQDzfI.png'),
(6, '<span lang=\"en\">athena</span>', '6', 'Il tavolo <span lang=\"en\">Athena</span> è il risultato di anni di ricerca del centro stile Altacorte. Ispirato agli stilemi architettonici dell&#039;antica Grecia con i doppi traversi di ordine ionico risulta essere un tavolo di design di grande presenza scenica proponendosi come vero protagonista dei vostri ambienti.', '../uploads/XwYf8IVRcx.jpeg', 'Tavolo su fondale vuoto', '../uploads/WxP8Z3FouM.jpeg'),
(7, 'siviglia ferro', '6', 'Il tavolo Siviglia con gambe in ferro curvate a freddo su pressa da 300 tonnellate è sinonimo di robustezza e allo stesso tempo di pulizia delle linee. Abbinabile a soli piani in legno massiccio risulta essere un degno compagno della vostra vita dove, come in natura, tutto si lega in un incontro perfetto.', '../uploads/QAbP65WKcf.jpeg', 'Tavolo su fondale vuoto', '../uploads/zpAT8FdMI7.jpeg'),
(8, 'composizione k25', '4', '<span lang=\"en\">Compab</span> ha scelto di presentare la collezione K25 con immagini realizzate solo in <span lang=\"en\">daylight</span>, la luce del giorno, quella vera, il modo più naturale e insieme più sincero, più onesto, meno artefatto. In fondo, semplicemente, è così che vedremo questo nostro ambiente, a casa nostra. E la luce naturale al suo variare di intensità e composizione nello scorrere del giorno è uno degli elementi che caratterizzano un luogo. Proposte anche creative non solo per il bagno ma per qualunque spazio della casa, ovunque tu desideri. Qui legni, laccati e piani vetro dalle <span lang=\"en\">texture</span> nuove creano una discreta eleganza.', '../uploads/s1Zar2GbWN.jpg', 'bagno moderno molto luminoso', '../uploads/XEfiDzltAQ.jpg'),
(9, '<span lang=\"en\">curry</span>', '1', 'Ancora una volta la cucina in legno di rovere anima i <span lang=\"en\">trend</span> attuali, grazie al telaio in legno massiccio di rovere nodato in finiture naturali uniche ed originali. L&#039;anima del legno è la sua forza, la sua bellezza. I nodi e le venature in evidenza sono la sua inconfondibile immagine. È disponibile anche in versione colorata in tutte le tonalità desiderate, nodi e venature rimarranno comunque visibili.', '../uploads/WeySlo1N4s.jpeg', 'cucina moderna molto luminosa', '../uploads/JYiuI23hvX.jpeg'),
(10, 'programma <span lang=\"en\">loft</span>', '1', 'Hai mai pensato di abbinare le superfici impiallacciate in legno di rovere ad altre in laminato in finitura cemento o pietra o ad altre ancora dai colori ultra opachi? Da oggi si può, grazie a un unico programma che ti dà la possibilità di combinare, integrare, abbinare, accostare colori e materiali diversi, per un risultato oltre ogni tua aspettativa. Cinque tipologie  che raccontano il carattere e la personalità della cucina: <span lang=\"en\">Loft Wood</span> con finitura impiallacciato in legno di rovere; <span lang=\"en\">Loft Soft</span> con finitura laccato <span lang=\"en\">soft touch</span>; <span lang=\"en\">Loft Wall</span> con  finitura laminato cemento; <span lang=\"en\">Loft Urban</span> con finitura laminato pietra e legno e <span lang=\"en\">Loft Line</span> con finitura laccato.', '../uploads/0et2NPV6F5.jpeg', 'cucina moderna in ambiente scuro', '../uploads/ubC0BcULM2.jpeg'),
(11, 'opale', '1', 'La nuovissima collezione Opale rappresenta l&#039;ultima generazione di soluzioni d&#039;arredo per l&#039;ambiente cucina: un&#039;unica ed innovativa materia riveste in perfetta continuità ante, <span lang=\"en\">top</span>, lavelli, pareti e anche pavimenti! Creazione di un ambiente in cui unità materica e cromatica rappresentano insieme il bello, il funzionale, il <span lang=\"en\">design</span>  delle cucine contemporanee. Costruzione di spazi dove termini come &quot;interruzione&quot; e &quot;seriale&quot; sono <span lang=\"en\">out</span>. Le parole chiave sono: personalizzazione, resistenza, <span lang=\"en\">ultra comfort</span>; con <span lang=\"en\">texture</span> realizzata completamente a mano da artigiani altamente specializzati.', '../uploads/z6mhR7jQrc.jpeg', 'ampia cucina moderna luminosa', '../uploads/cUOdRYtgV6.jpeg'),
(12, 'maestrale', '1', 'La nuova proposta della famiglia Maestrale è una cucina dal forte carattere. La finitura carbone, il <span lang=\"en\">top</span> in <span lang=\"en\">unicolor</span> e i dettagli in ferro creano nell&#039;insieme una cucina dal gusto industriale.', '../uploads/lLvcP9FEfq.jpeg', 'luminosa cucina moderna in legno', '../uploads/Heu1W0LGFQ.jpeg'),
(13, '<span lang=\"en\">horizon</span> 912', '2', 'Diversi modi di interpretare la zona giorno abbinando superfici materiche a cromie di tendenza. Le composizioni si articolano in geometrie leggere invitando ad utilizzare lo spazio in modo libero e creativo. Grande libertà di mischiare materiali, altezze e profondità integrando le mensole a pensili sagomati.', '../uploads/LCV2nU4NoY.jpeg', 'salotto luminoso moderno', '../uploads/jloZ70gID8.jpeg'),
(14, '<span lang=\"en\">horizon</span> 942', '2', 'Fianchi, ripiani e pensili si combinano liberamente all&#039;interno dei sistemi libreria, dando vita ad architetture da parete sempre diverse. Elementi a giorno di differenti dimensioni e dal sottile spessore si possono inserire per accogliere oggetti favorendo la personalizzazione con il colore, tono su tono o a contrasto.', '../uploads/SjR02EwYkA.jpeg', 'salotto con grande libreria', '../uploads/vIeRwYj2hs.jpeg'),
(15, 'atlante A064', '2', 'Contenitori a terra e elemento anta <span lang=\"en\">Media Box</span> in laccato opaco carta da zucchero 155. Pensili <span lang=\"en\">Diagonal</span> in materico cemento chiaro con elementi in metallo finitura carta da zucchero 155. <span lang=\"en\">Boiserie Diagonal</span> in materico cemento chiaro e materico nodato chiaro con mensoloni in materico nodato chiaro. <span lang=\"en\">Panka</span> in materico nodato chiaro.', '../uploads/UfDqtyHYGJ.jpeg', 'ampio salotto luminoso', '../uploads/Aw5VGznRJ2.jpeg'),
(16, 'soggiorno <span lang=\"en\">voltan</span>', '2', 'Porta televisiore, mobili contenitori e cassettiere: la pareti attrezzate moderne soggiorno arredano con stile e versatilità il salotto. Le asimmetrie, l&#039;alternanza tra elementi verticali e orizzontali e tra superfici lisce e decorate creano delle composizioni di grande impatto. Inoltre la modularità degli elementi consente la massima libertà compositiva, mentre le incisioni e i colori aumentano le possibilità di personalizzazione.', '../uploads/oKGVMj8lwm.jpeg', 'soggiorno moderno e tv', '../uploads/tRZHvbzjNe.jpeg'),
(17, 'salotto m07', '2', 'Una parete <span lang=\"en\">living</span> che funge sia da libreria che da scrivania soggiorno. È ideale per un <span lang=\"en\">living</span> dalla doppia funzione o può essere anche posizionata in una cameretta. I colori a contrasto la rendono ideale per ogni tipo di arredamento. Un salotto con scrivania è la soluzione ideale per chi deve lavorare da casa e ha bisogno di una postazione fissa.', '../uploads/ZnPzq4aYsl.jpeg', 'salotto con scrivania in legno', '../uploads/6DoBSfQpZV.jpeg'),
(18, 'letto mercurio', '3', 'Mercurio: il letto per chi ama il calore e la raffinatezza del legno massello unito ad uno stile deciso e minimalista. Soluzione altamente modulare e versatile che abbraccia stili &quot;<span lang=\"en\">minimal</span>&quot; e suggestioni materiche, ricca di contenuti per le qualità espresse da realizzazioni sartoriali.', '../uploads/tfvZziqKLc.jpeg', 'letto moderno in ampia camera', '../uploads/GCYmD5Jwc7.jpeg'),
(19, 'armadi battente', '3', '<span lang=\"en\">Mobilgam</span> apre le porte alla scoperta di sempre nuovi modi di interpretare la zona della &quot;notte&quot; che amplia il suo orizzonte verso scenari aperti, contaminati dalla luce. I finali a due profondità in Frassino Petrolio, assecondano lo spazio dell&#039;ambiente architettonico con grazia e intelligenza.', '../uploads/9YSyeodKtf.jpeg', 'ampio armadio in camera luminosa', '../uploads/WClenaNAxI.jpeg'),
(20, 'gruppo <span lang=\"en\">capitol</span>', '3', 'Dal comò a tre cassetti ai comodini, il <span lang=\"en\">design</span> è minimale, ma un dettaglio si fa notare: le superfici lisce e senza maniglie sono integrate a un fianco con colore a contrasto. La notte è più originale.', '../uploads/P4gATBNH7D.jpeg', 'ampia camera da letto', '../uploads/P4mShv25OI.jpeg');

INSERT INTO `IMAGE` (`id`, `path`, `alt`, `prod`) VALUES
(4, '../uploads/6O3Atkengz.jpg', 'elementi componibili del soggior', 2),
(5, '../uploads/PEg1hSwf3b.jpg', 'cassettone illuminato', 2),
(6, '../uploads/geLNbcBhK4.jpg', 'pensili componibili', 2),
(7, '../uploads/xKB2bOcDW5.jpg', 'armadio a specchio con TV', 3),
(8, '../uploads/BLR4QPCVkq.jpg', 'dettaglio tv incorporata', 3),
(9, '../uploads/PdKyk5vE7e.jpg', 'cuscino e bracciolo', 4),
(10, '../uploads/L53rZlXqai.jpg', 'divano visto dall&#039;alto', 4),
(11, '../uploads/aePMJZ0DGU.jpg', 'divano in ampio salotto luminoso', 4),
(12, '../uploads/nt0Xqu8DTr.jpg', 'divano in spazio vuoto', 4),
(13, '../uploads/PQSDIuzjfq.png', 'divano su fondale vuoto', 5),
(14, '../uploads/ekLq6YyUz3.png', 'schienale e braccioli del divano', 5),
(15, '../uploads/2B4GO9lv6i.png', 'divano dall&#039;alto', 5),
(16, '../uploads/mVRQSKYfLW.jpeg', 'tavolo con accessori e sedia', 6),
(17, '../uploads/23j5CowDI6.jpeg', 'angolo del tavolo con oggetti', 7),
(18, '../uploads/fT8oXkV4xc.jpeg', 'tavolo in stanza luminosa', 7),
(19, '../uploads/exzXLGSg60.jpeg', 'tavolo con accessori e lampada', 7),
(20, '../uploads/DIkNgJ249E.jpg', 'cassetto aperto con oggetti', 8),
(21, '../uploads/d64SlHhtMD.jpg', 'cassetto semi aperto con oggetti', 8),
(22, '../uploads/pdnHXQDOes.jpg', 'scaffali del bagno', 8),
(23, '../uploads/10wRgeLBrP.jpg', 'bagno con cassetti luminosi', 8),
(24, '../uploads/3jpc4AJxF5.jpeg', 'piano di lavoro della cucina', 9),
(25, '../uploads/nJU30V1gES.jpeg', 'dettaglio sugli elettrodomestici', 9),
(27, '../uploads/BeQCU3MZRa.jpeg', 'dettaglio cassetti della cucina', 9),
(28, '../uploads/EbT102p75P.jpeg', 'cucina con materiale diverso', 9),
(29, '../uploads/nYbXwZg3dN.jpeg', 'open space luminoso', 9),
(30, '../uploads/Xt0CufrR4e.jpeg', 'pavimento, cassetti e rubinetto', 9),
(31, '../uploads/kEP20SVw7M.jpeg', 'grandi ante della cucina', 10),
(33, '../uploads/x5iXMo8g3r.jpeg', 'cucina in ambiente luminoso', 10),
(34, '../uploads/zsfjXSIiW6.jpeg', 'cucina moderna più chiara', 10),
(35, '../uploads/uJfhnxQKjW.jpeg', 'cucina moderna molto luminosa', 10),
(36, '../uploads/ERlF2HQAfI.jpeg', 'cucina moderna marmorea', 10),
(37, '../uploads/WbspUYPNvl.jpeg', 'lavandino e ampia finestra', 10),
(38, '../uploads/v90Ry1W5gq.jpeg', 'piano di lavoro in marmo', 10),
(39, '../uploads/YU9i20z7qI.jpeg', 'cucina moderna luminosa', 11),
(40, '../uploads/D5a8nyEgf2.jpeg', 'isola della cucina', 11),
(41, '../uploads/pSUyYLnTd8.jpeg', 'cucina moderna con scale dietro', 11),
(42, '../uploads/cCRV0Gqd2U.jpeg', 'piano in marmo', 12),
(43, '../uploads/ek56qJBQVT.jpeg', 'lavello su piano in marmo', 12),
(44, '../uploads/5wxrqflF2J.jpeg', 'cucina moderna in legno', 12),
(45, '../uploads/8ztmxIQrEY.jpeg', 'cappa e stoviglie su ripiano', 12),
(46, '../uploads/Putn9zCBZr.jpeg', 'ripiani in marmo sul muro', 13),
(47, '../uploads/W0QATeC9V1.jpeg', 'ripiani in marmo misto legno', 13),
(48, '../uploads/Z60lFTQxOB.jpeg', 'scaffale con oggetti e libri', 14),
(49, '../uploads/LyVpoZ9ztR.jpeg', 'scaffale con oggetti e libri', 14),
(50, '../uploads/WdkYB3bxjS.jpeg', 'ripiani del salotto in legno', 15),
(51, '../uploads/xfnDN7oYTi.jpeg', 'ripiani, ante e mensole', 15),
(52, '../uploads/mneyikw3ro.jpeg', 'soggiorno su spazio molto ampio', 16),
(53, '../uploads/mSlHEBsAzo.jpeg', 'soggiorno moderno luminoso', 16),
(54, '../uploads/xg7J21Ci4A.jpeg', 'soggiorno moderno e tv', 16),
(55, '../uploads/RQP2FvT3Nn.jpeg', 'soggiorno moderno luminoso', 16),
(56, '../uploads/xA0dukImlF.jpeg', 'ripiani del salotto in legno', 17),
(57, '../uploads/fipNroRJTk.jpeg', 'scrivania in legno con oggetti', 17),
(58, '../uploads/1u07rYgZMl.jpeg', 'ripiani e armadi del salotto', 17),
(59, '../uploads/DjiInlJpTe.jpeg', 'sedia e scrivania', 17),
(60, '../uploads/632MjWQXA5.jpeg', 'scrivania con ampia libreria', 17),
(61, '../uploads/HBl9Ti2qaW.jpeg', 'comodo in legno con lampada', 18),
(62, '../uploads/nd0op38XMk.jpeg', 'ampia camera da letto luminosa', 18),
(64, '../uploads/LxmkCSFYvU.jpeg', 'armadio su ampia camera', 19),
(65, '../uploads/c4fD1MC9yP.jpeg', 'ampio armadio spigoloso', 19),
(66, '../uploads/gBzVFmL92b.jpeg', 'comò con oggetti', 20),
(67, '../uploads/EjzoPJOdwR.jpeg', 'comodino con vegetazione', 20),
(70, '../uploads/9l8QmSE3cG.jpeg', 'comò con oggetti', 20),
(71, '../uploads/VDgjRKetnm.jpeg', 'letto e comodino', 20),
(72, '../uploads/xSIugFf7yn.jpg', 'luminosa cucina classica', 1),
(73, '../uploads/q5Qkur1OfU.jpg', 'ripiani ampia cucina', 1),
(74, '../uploads/wLa0Mt5R3B.jpg', 'tavolo e piano da lavoro', 1);
