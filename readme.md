# Admin system

Aplikacja stworzona na potrzeby serwisu LSVRP.pl. Służy do zdalnego zarządzania serwerem gry. Korzysta z frameworka webowego opartego o model MVC **Laravel**.

Aplikacja korzysta z autoryzacji OAuth 2, dzięki czemu do korzystania z panelu potrzebne jest tylko konto na forum (Invision Power Services 4.0>).
W aplikacji użyto frameworka JavaScript **Vue.js** do obsługi dynamicznego interfejsu. Dane przechowywane są w bazie MySQL.
Po zmianie danych poza edycją ustawień w bazie danych aplikacja wysyła poprzez protokół UDP informacje do serwera.
Po odebraniu takiej wiadomości serwer automatycznie przeładowuje zmienione dane.