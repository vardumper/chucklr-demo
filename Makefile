up:
	symfony server:stop
	docker compose up -d
	symfony server:start -d --no-tls
	sleep 15
	symfony console messenger:setup-transports
	symfony run -d symfony console messenger:consume async
	symfony open:local
	symfony server:log
down:
	docker compose down
	symfony server:stop