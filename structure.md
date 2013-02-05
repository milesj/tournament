Tournament Structure

```
Regions
Games
	-> Leagues
		-> Divisions
			-> Events
				-> Matches
```

Example:

```
US, EU, KR
League of Legends
	-> West
		-> Open
			-> Season 2
		-> Pro
			-> Season 1
	-> Central
		-> Open
			-> Season 5
```

Possible URLs:

/
	- List all leagues
		- Game it belongs to
		- Divisions within each league
		- Current and upcoming event
	- Top players
	- Top teams
/<league>
	- Single league info
	- Divisions
	- Events (past, current, etc)
	- Statistics
/<league>/<event>
	- Event league info
	- Brackets
/<league>/<event>/teams
	- Teams in event
/<league>/<event>/players
	- Players in event
/<league>/<event>/matches
	- Matches in event
/schedule
	- Events calendar
	- List matches and times
/team/<id>
	- Team profile
/teams
	- List of teams
/player/<id>
	- Player profile
/players
	- List of players