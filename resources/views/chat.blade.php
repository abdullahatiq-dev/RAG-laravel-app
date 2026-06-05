<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RAG AI Assistant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex flex-col h-screen">

    <!-- Header -->
    <div class="bg-white shadow p-4 text-lg font-semibold">
        🤖 RAG AI Assistant
    </div>

    <!-- Chat Container -->
    <div id="chatBox" class="flex-1 overflow-y-auto p-4 space-y-4">
        
        <!-- Messages will appear here -->

    </div>

    <!-- Input -->
    <div class="bg-white p-4 border-t">
        <div class="flex gap-2">
            <input id="questionInput"
                   type="text"
                   placeholder="Ask something..."
                   class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <button onclick="sendMessage()"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Send
            </button>
        </div>
    </div>

</div>

<script>
async function sendMessage() {
    const input = document.getElementById('questionInput');
    const chatBox = document.getElementById('chatBox');

    const question = input.value.trim();
    if (!question) return;

    // Add user message
    chatBox.innerHTML += `
        <div class="flex justify-end">
            <div class="bg-blue-600 text-white px-4 py-2 rounded-lg max-w-md">
                ${question}
            </div>
        </div>
    `;

    input.value = '';

    // Loading message
    const loadingId = 'loading-' + Date.now();
    chatBox.innerHTML += `
        <div id="${loadingId}" class="flex justify-start">
            <div class="bg-gray-200 px-4 py-2 rounded-lg max-w-md">
                Thinking...
            </div>
        </div>
    `;

    chatBox.scrollTop = chatBox.scrollHeight;

    try {
        const response = await fetch('/api/ask', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ question })
        });

        const data = await response.json();
        console.log(data.answer);
        // Remove loading
        document.getElementById(loadingId).remove();

        // Add AI response
        chatBox.innerHTML += `
            <div class="flex justify-start">
                <div class="bg-white shadow px-4 py-2 rounded-lg max-w-md">
                    ${data.answer}
                    
                    <div class="text-xs text-gray-500 mt-2">
                        Sources: ${data.matches.length}
                    </div>
                </div>
            </div>
        `;

    } catch (error) {
        document.getElementById(loadingId).remove();

        chatBox.innerHTML += `
            <div class="text-red-500">Error fetching response</div>
        `;
    }

    chatBox.scrollTop = chatBox.scrollHeight;
}


document.getElementById('questionInput')
.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') sendMessage();
});
</script>

</body>
</html>