<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tivoli API Guide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <main class="max-w-4xl mx-auto px-6 py-12">
        <h1 class="text-4xl font-bold mb-6">Welcome to WU24 Tivoli API platform!</h1>
        <p class="text-lg mb-8">
            This documentation is for teams who want to integrate their mini-games into the Tivoli system.
            Once your game is connected, users will be able to launch it from <a href="https://tivoli.yrgobanken.vip/" class="text-blue-600 underline hover:text-blue-800">
                <strong>Tivoli</strong>
           </a>and send gameplay data (like wins) securely back to our backend.
        </p>

        <h2 class="text-2xl font-semibold mb-4">Requirements Before You Start</h2>
        <p class="mb-4">Before you can connect your game:</p>
        <ol class="list-decimal list-inside mb-6 space-y-2">
            <li>You must request an <strong>API Key</strong> from the Tivoli backend team (this key is unique to your group).</li>
            <li>Your game must be able to:
                <ul class="list-disc list-inside ml-5 mt-2 space-y-1">
                    <li>Accept a <strong>JWT token</strong> from the frontend.</li>
                    <li>Send authenticated requests back to our API using both:
                        <ul class="list-disc list-inside ml-5 mt-1">
                            <li>A valid <strong>JWT</strong> (from the user)</li>
                            <li>Your <strong>API Key</strong></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ol>

        <h2 class="text-2xl font-semibold mb-4">Understanding the JWT Token</h2>
        <p class="mb-4">
            A <strong><a href="https://auth0.com/docs/secure/tokens/json-web-tokens" class="text-blue-600 underline hover:text-blue-800">JSON Web Token</a></strong> is a secure and compact token used to verify the identity of a user.
        </p>

        <h3 class="text-xl font-semibold mt-6 mb-2">Purpose</h3>
        <p class="mb-4">
            In the Tivoli system, the JWT token ensures that the player interacting with your game is logged in and authorised to play.
            It must be included with every request your game sends to the Tivoli API.
        </p>

        <h3 class="text-xl font-semibold mt-6 mb-2">How It’s Created</h3>
        <ul class="list-disc list-inside mb-6 space-y-2">
            <li>The JWT is created by the Tivoli backend when the user logs in.</li>
            <li>The token contains digitally signed data (e.g. user ID, expiration time, etc.).</li>
            <li>Your game does not need to parse or validate the token — only attach it in requests.</li>
        </ul>

        <h2 class="text-2xl font-semibold mb-4">Authentication Rules</h2>
        <ul class="list-disc list-inside mb-6 space-y-2">
        <li>
        A <strong>JWT token</strong> is required for all <strong>user-specific requests</strong>,
        such as reporting a win.
        </li>
        <li>
        An <strong>API key</strong> is required for all <strong>authenticated group-level requests</strong>
        (these are not used by regular users).
    </li>
    <li>
        Some endpoints are <strong>publicly accessible</strong>, such as viewing an amusement via an embedded iframe.
    </li>
</ul>


        <h2 class="text-2xl font-semibold mb-4">Receiving the JWT (User Login)</h2>
        <p class="mb-6">
            When a player launches your game via the tivoli, they will be authenticated, and a JWT token will be passed to your game.
            Your game must read this token and store it for use in API requests to Tivoli.
            Otherwise the player can’t be identified and no transaction can go through.
        </p>

        <h2 class="text-2xl font-semibold mb-4">Using the Tivoli API</h2>
        <p class="mb-4">
           Please refer to our project <strong><a href="https://github.com/Viktor-TPD/enter-paneer-game" class="text-blue-600 underline hover:text-blue-800">Enter Paneer Game</a></strong> as a resource to see how we've worked with JWT-token authentication and API-requests. 
        </p>

      
        <h2 class="text-2xl font-semibold mb-4">
        Test Account 
     </h2>

     <p class="mb-4">
            Email: rune@yrgobanken.vip
        </p>
        <p class="mb-4">
            Password: password
        </p>

        <h2 class="text-2xl font-semibold mb-4">
            Yaml
        </h2>
        <p class="mb-4">
            Here you can download the <strong><a href="/tivoli.yaml" class="text-blue-600 underline hover:text-blue-800" download>yaml</a></strong> file to see all the API endpoints! Go to <strong><a href="https://editor.swagger.io/" class="text-blue-600 underline hover:text-blue-800">Swagger</a></strong> and import the file. Now you should see the Tivoli centralbank API. You only need to post transactions to the API. Follow the code example above to integrate it into your code seamlessly.
        </p>
        <h2 class="text-2xl font-semibold mb-4">OBS!</h2>
        <p class="mb-4"> We have discovered a small mistake regarding stamps. Unfortunately, you can only issue a stamp, which is firmly linked to a specific metal and an animal. This means that the possibility of adding a metal to a rammer will not exist. However, you can exchange any stamp you give out when you update your attraction or game, for a cost of $4. That cost is deducted from you and your team members. Have a good time!</p>

    </main>
    <footer class="mt-16 py-6 border-t border-gray-200">
    <div class="max-w-4xl mx-auto px-6">
        <div class="flex justify-between items-center">
            <p class="text-gray-600">&copy; {{ date('Y') }} Team Backend</p>
            <a href="{{ route('admin.login') }}" class="text-blue-600 hover:underline">Admin Access</a>
        </div>
    </div>
</footer>
</body>
</html>







