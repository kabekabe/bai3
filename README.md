# Bezpieczeństwo aplikacji internetowych
Projekt 3 - Generowanie haseł

Zadania:

Dla całego projektu za nieistotne należy uznać problemy związane z bezpieczeństwem połączenia z bazą danych oraz pobierania z niej danych, które będą rozwijane na kolejnych zajęciach. Dla wszystkich zadań jako domyślną metodę przesyłania parametrów zastosuj metodę GET.

1. Udostępnij użytkownikowi możliwość logowania przy użyciu losowo wybranych fragmentów haseł. Hasła powinny być przechowywane po stronie serwera z wykorzystaniem funkcji hashujących oraz soli. Hasła powinny spełniać następujące kryteria:

	• długość hasła od 8 do 16 znaków,

	• co najmniej 5 znaków przy zapytaniu o fragment hasła,

	• maksymalnie zapytanie o fragment składający się z połowy hasła jednak nie mniej niż z 5-ciu znaków,

	• należy sprawdzać ustawienie podawanych znaków na właściwych pozycjach we fragmencie hasła,

	• zmiana aktualnego hasła cząstkowego następuje po prawidłowym zalogowaniu - wymagana jest dodatkowa weryfikacja podczas próby zmiany hasła,

	• co najmniej dziesięć haseł cząstkowych dla każdego użytkownika,

	• należy uwzględnić wszystkie ograniczenia związane z odstępami czasu logowania oraz użytkownikami nienależącymi do systemu z poprzednich zajęć.

2. Udostępnij użytkownikowi możliwość zmiany hasła.

Testy:

1. Czy dla użytkownika zmienia się wymagane hasło cząstkowe po wykonaniu zapytania? (nie)

http://baipb.cba.pl/Ps08.php?login=ala&action=Dalej

2. Czy dla hasa alamakota i maski 111110000 użytkownik zostanie zalogowany? (nie)

http://baipb.cba.pl/Ps08.php?pass[5]=a&pass[6]=l&pass[7]=a&pass[8]=m&pass[9]=a&login=ala&action=Zaloguj

3. Czy dla hasa alamakota i maski 111110000 użytkownik zostanie zalogowany? (tak)

http://baipb.cba.pl/Ps08.php?pass[0]=a&pass[1]=l&pass[2]=a&pass[3]=m&pass[4]=a&login=ala&action=Zaloguj

4. Czy użytkownikowi zmienia się wymagane hasło cząstkowe po nieprawidłowym logowaniu? (nie)

5. Czy użytkownikowi zmienia się wymagane hasło cząstkowe po prawidłowym logowaniu? (tak)

