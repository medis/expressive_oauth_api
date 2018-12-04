# Create username/password user
Send post to http://localhost/api/user/create with payload:
- username
- password
- first_name
- last_name
- client_secret
- client_id

# Return user details
http://localhost/api/user/UUID

# Update user details
send post to http://localhost/api/user/UUID with payload that consists any of these:
- username
- password: password (automatically re-hash password)
- first_name
- last_name

# Get OAuth token
&nbsp;
Send post to http://localhost/oauth with payload:
- grant_type: password
- username
- password
- client_id
- client_secret
- scope: test

# Check OAuth access
&nbsp;
Send post to http://localhost/api/ping with heading:
- Authorization: Bearer *access token*