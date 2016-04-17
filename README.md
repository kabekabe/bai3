# Bezpieczeństwo aplikacji internetowych
Projekt 2 - Implementacja formularz logowania

Zadania:
Dla całego projektu za nieistotne należy uznać problemy związane z  bezpieczeństwem połączenia z bazą danych oraz pobierania z niej danych, które będą rozwijane na kolejnych zajęciach. Dla wszystkich zadań jako domyślną metodę przesyłania parametrów zastosuj metodę GET.

1. Zaimplementuj formularz logowania użytkowników (1 punkt).

2. Udostępnij użytkownikowi możliwość włączenia/wyłączenia blokowania konta po n nieudanych próbach logowania gdzie n jest dowolną liczną naturalną. Jakie niesie za sobą zagrożenia takie rozwiązanie? Jak powinna wyglądać procedura odblokowania konta? Czy wykorzystanie sekretnego pytania oraz odpowiedzi jest dobrym rozwiązaniem? Nie przechowuj informacji o liczbie nieudanych prób logowania w cookes!

3. Zaimplementuj logowanie zdarzeń takich jak:
	a) data ostatniego nieudanego logowania,
	b) data ostatniego udanego logowania (ostatnie a nie obecne logowanie),
	c) liczba nieudanych logowań od ostatniego poprawnego logowania.
	
4. Dane z powyższych punktów udostępnij użytkownikowi (1 punkt).

5. Zaimplementuj opóźnienie po nieudanym logowaniu wyliczane w zależności od liczby prób nieudanych logowań (nie używaj komendy sleep ani innych zawieszających proces/wątek po stronie serwera) (1 punkt).

6. Jakie informacje udostępniasz użytkownikowi przy nieudanej próbie logowania? System nie powinien udostępniać informacji umożliwiających (pomagających) dokonanie włamania do projektowanego systemu.

7. Należy przechowywać informacje o próbie logowania do systemu użytkowników nieistniejących. Zachowanie związane z opóźnieniem logowania identyczne jak w przypadku użytkowników zarejestrowanych w systemie. Jaki ma to związek z powyższym punktem? (1 punkt)

