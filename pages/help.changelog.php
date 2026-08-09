<h1>Changelog</h1>
<p>1.1.1:</p>
<ul>
	<li>Wartung: Interne CI-Dateien im Verzeichnis <code>.github</code> (GitHub Actions, Dependabot) werden nicht mehr mit dem Installer-Paket ausgeliefert.</li>
	<li>Bugfix: Der Online/Offline-Schalter in der Backend-Liste funktioniert wieder. Beim Erzeugen des CSRF-geschützten Status-Links wurde der Platzhalter für die Datensatz-ID URL-kodiert und dadurch nicht mehr durch die echte ID ersetzt.</li>
	<li>Barrierefreiheit/Sicherheit: In neuen Tabs öffnende Partner-Logo-Links (Modul 25-1) erhalten jetzt durchgängig <code>rel="noopener noreferrer"</code>.</li>
	<li>Security/Bugfix: Die <code>save()</code>-Methode in <code>lib/Partner.php</code> verwendet jetzt gebundene Parameter statt SQL-String-Konkatenation (Felder <code>picture</code>, <code>url</code>, <code>online_status</code>); IDs werden nach <code>int</code> gecastet. Verhindert SQL-Injection und <code>rex_sql_exception</code> bei Werten mit Anfuehrungszeichen.</li>
</ul>
<p>1.1.0:</p>
<ul>
	<li>Backend: Abbrechen-Buttons in Partner- und Kategorieformularen fuehren jetzt wieder zur Liste.</li>
	<li>Backend: CSRF-Schutz fuer Speichern-, Loesch- und Statusaktionen der Partnerverwaltung ergaenzt.</li>
	<li>Backend: CSRF-Schutz fuer Modul-Installation, -Update und -Deinstallation auf der Setup-Seite ergaenzt.</li>
	<li>Neues Modul 25-2 "D2U Business Partner - Business Partner (BS5)" hinzugefügt.</li>
	<li>Modul 25-1 als "(BS4, deprecated)" markiert. Die BS4-Variante wird im nächsten Major Release entfernt.</li>
	<li>README-/Hilfetexte und Setup auf BS5-Rollout angepasst.</li>
	<li>Benötigt d2u_helper &gt;= 2.1.0.</li>
	<li>Backend-Listen sortierbar gemacht und Standardsortierungen von SQL-Queries auf <code>rex_list</code>-<code>defaultSort</code> umgestellt.</li>
	<li>Security: Die <code>media-is-in-use</code>-Extension-Points in <code>boot.php</code> verwenden jetzt gebundene Parameter statt SQL-String-Konkatenation mit <code>addslashes()</code>.</li>
	<li>Security: Die <code>save()</code>-Methoden in <code>lib/Category.php</code> und <code>lib/Partner.php</code> verwenden jetzt gebundene Parameter statt SQL-String-Konkatenation mit <code>addslashes()</code>.</li>
	<li>Security: Modul-Ausgaben (<code>modules/25/1/output.php</code>, <code>modules/25/2/output.php</code>) härten Backend-Eingaben gegen XSS via <code>rex_escape()</code> für HTML- und Attributausgaben sowie Typecasts (<code>(int)</code>) für numerische Felder; externe Links erhalten <code>rel="noopener"</code>.</li>
	<li>Security: In <code>pages/category.php</code> wird der Partner-Name in der Lösch-Warnung (Kategorie wird noch verwendet) jetzt mit <code>rex_escape()</code> ausgegeben.</li>
	<li>Bugfix: Modul 25-1 ("BS4") filterte Partner nicht nach Kategorie, weil eine undefinierte Variable an den Kategorie-Konstruktor übergeben wurde. Die Kategorie-ID wird jetzt korrekt aus dem Modulfeld gelesen.</li>
	<li>Bugfix: <code>update.php</code> verwendete eine nicht definierte <code>$sql</code>-Variable, wodurch das Datenbank-Update mit einem Fatal Error abbrach. Die <code>rex_sql</code>-Instanz wird jetzt korrekt erzeugt.</li>
	<li>Bugfix: In <code>pages/category.php</code> wurde <code>Category::delete()</code> mit einem überflüssigen Argument aufgerufen; der Aufruf ist nun korrekt parameterlos.</li>
	<li>Bugfix: Korrektur des PHPDoc-Rückgabetyps von <code>rex_d2u_partner_article_is_in_use()</code> (string statt array) und Entfernung der wirkungslosen <code>return $this;</code>-Anweisung im <code>Partner</code>-Konstruktor.</li>
</ul>
<p>1.0.1:</p>
<ul>
	<li>PHP-CS-Fixer Code Verbesserungen.</li>
	<li>Anpassungen an Publish Github Release to Redaxo.</li>
	<li>Bugfix: Rechteproblem behoben.</li>
	<li>Bugfix: Beim Löschen von Artikeln und Medien die vom Addon verlinkt werden wurde der Name der verlinkenden Quelle in der Warnmeldung nicht immer korrekt angegeben.</li>
	<li>Konvertierung der Datenbanktabellen zu utf8mb4.</li>
	<li>Bugfix: Fehler beim Speichern von Namen mit einfachem Anführungszeichen behoben.</li>
</ul>
<p>1.0:</p>
<ul>
	<li>Initiale Version.</li>
</ul>