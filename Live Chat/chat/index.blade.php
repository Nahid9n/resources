<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { height: 100vh; background: #f1f3f6; display: flex; justify-content: center; align-items: center; }
        .chat-wrapper {
            width: 80%;
            height: 100vh;
            background: #fff;
            border-radius: 0;
            overflow: hidden;
            display: flex;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        /* md এর নিচে গেলে width হবে 100% */
        @media (max-width: 768px) {
            .chat-wrapper {
                width: 100%;
            }
        }
        /* Sidebar */
        .chat-sidebar { width: 400px; border-right: 1px solid #e5e5e5; display: flex; flex-direction: column; background: #fafafa; }
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid #e5e5e5; background: linear-gradient(135deg, #0d6efd, #6c63ff); color: #fff; }
        .sidebar-header img { width: 70px; height: 70px; border-radius: 50%; border: 3px solid #fff; margin-bottom: 10px; }
        .chat-list { flex: 1; overflow-y: auto; padding: 10px; }
        .chat-user { padding: 12px; border-radius: 12px; margin-bottom: 8px; cursor: pointer; transition: 0.2s; position: relative; }
        .chat-user:hover { background: #e9ecef; }
        .unread-count { position: absolute; top: 10px; right: 10px; background: red; color: #fff; font-size: 12px; border-radius: 50%; padding: 2px 6px; }

        /* Chat box */
        .chat-container { flex: 1; display: flex; flex-direction: column; }
        .chat-header { padding: 15px 20px; border-bottom: 1px solid #e5e5e5; background: #f8f9fa; font-weight: 600; align-items: center; }
        .messages-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f1f3f6;
            display: flex;
            flex-direction: column;
        }

        /* Common message style */
        .message {
            display: inline-block;
            margin-bottom: 12px;
            padding: 10px 16px;
            border-radius: 18px;
            line-height: 1.4;
            word-wrap: break-word;
            max-width: 70%;
        }

        /* Admin (right side) */
        .message.admin {
            background: #0d6efd;
            color: #fff;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
            text-align: right;
        }

        /* Guest (left side) */
        .message.guest {
            background: #84909c;
            color: #fff;
            align-self: flex-start;
            border-bottom-left-radius: 4px;
            text-align: left;
        }

        .message-input { border-top: 1px solid #e5e5e5; padding: 12px; background: #fff; }
        .message-input input { border-radius: 30px; }
        .message-input button { border-radius: 30px; padding: 0 20px; }

        .chat-user {
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s ease-in-out; /* smooth effect */
            position: relative;
        }

        /* Hover effect */
        .chat-user:hover {
            background: #e9ecef !important;
            transform: translateY(-2px); /* হালকা উপরে উঠবে */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        /* Active state */
        .chat-user.active {
            background-color: #0d6efd !important;
            color: #fff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }


        /* Responsive */
        @media (max-width: 768px) {
            .chat-sidebar { display: none; }
        }
    </style>
</head>
<body>
<div class="chat-wrapper">
    <!-- Sidebar (Offcanvas on Mobile) -->
    <div class="chat-sidebar d-none d-md-flex" id="sidebarDesktop">
        <div class="sidebar-header">
            <img src="https://i.pravatar.cc/100?img=12" alt="Admin">
            <h6>{{ auth()->user()->name ?? 'Admin' }}</h6>
        </div>
        <div class="chat-list" id="chatList">
            @foreach($sessions as $s)
                <div class="chat-user text-dark"
                     style="{{$s->status == 'closed' ? 'background-color: rgba(244,107,88,0.4)': 'background-color: rgba(216,211,211,0.6)'}}"
                     data-id="{{ $s->id }}"
                     data-name="{{ $s->guest_name }}"
                     data-phone="{{ $s->guest_phone }}">
                    <div class="row">
                        <div class="col-md-7">
                            <strong><small>{{ $s->guest_name }}</small></strong><br>
                            <small>{{ $s->guest_phone }}</small>
                        </div>
                        <div class="col-md-5 text-end">
                            <strong class="counter">
                                @php
                                    $c = $s->messages->where('seen_by_admin',0)->where('sender_type','guest')->count();
                                @endphp
                                @if($c != 0)
                                    <small class="bg-success text-white p-1 countmsg" style="border-radius: 100%">{{ $c }}</small>
                                @endif
                            </strong><br>

                            <small class="{{$s->status == 'closed' ? 'text-white': 'text-dark'}}" style="font-size: 10px;">{{ \Carbon\Carbon::parse($s->created_at)->format('d-m-y h:i A') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Mobile Offcanvas Trigger -->
    <div class="d-md-none p-2" style="height: 100px">
        <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">Chats</button>
    </div>

    <!-- Mobile Sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <img src="https://i.pravatar.cc/100?img=12" alt="Admin">
            <h6>{{ auth()->user()->name ?? 'Admin' }}</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" id="mobileChatList">
            @foreach($sessions as $s)
                <div class="chat-user" data-id="{{ $s->id }}" data-name="{{ $s->guest_name }}">
                    <strong>{{ $s->guest_name }}</strong><br>
                    <small>{{ $s->guest_phone }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Chat Box -->
    <div class="chat-container">
        <div class="chat-header" id="chatHeader">
            <span>Select a chat</span>
        </div>
        <div class="messages-body" id="messageBody"></div>
        <div class="message-input d-flex">
            <input type="text" id="msgInput" class="form-control me-2" placeholder="Type a message...">
            <button class="btn btn-primary" id="sendBtn">Send</button>
            <button class="btn btn-danger mx-1" id="closeBtn">Close</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let activeSession = null;

    // Sidebar Click (Load Messages)
    $(".chat-user").on("click", function() {

        activeSession = $(this).data("id");
        let name = $(this).data("name");
        let phone = $(this).data("phone");
        $("#chatHeader").empty().append(`<span> ${name} </span> <br> <span>${phone}</span>`);
        // $("#chatHeader").text("" + phone);

        $(".chat-user").removeClass("active");
        $(this).addClass("active");

       let url = "{{ route('admin.chat.seen.status') }}";
        $.get(url, {
            chat_session_id: activeSession,
        });

        // Offcanvas hide safely
        let offcanvasEl = document.querySelector('.offcanvas');
        if (offcanvasEl) {
            // যদি আগে instance না থাকে, নতুন create করি
            let offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl)
                || new bootstrap.Offcanvas(offcanvasEl);
            offcanvas.hide();
        }

        loadMessages();

    });

    // Load messages from server
    function loadMessages() {
        if (!activeSession) return;

        let url = "{{ route('admin.chat.fetch', ':sid') }}".replace(':sid', activeSession);

        $.get(url, function(res) {
            let $msgBody = $("#messageBody");

            // refresh এর আগে scroll অবস্থান চেক
            let isAtBottom = $msgBody.scrollTop() + $msgBody.innerHeight() >= $msgBody[0].scrollHeight - 1;

            let html = '';
            res.messages.forEach(msg => {
                let cls = (msg.sender_type === 'admin') ? 'admin' : 'guest';
                html += `<div class="message ${cls}">${msg.message}</div>`;
            });

            $msgBody.html(html);

            if (isAtBottom) {
                $msgBody.scrollTop($msgBody[0].scrollHeight);
            }

            // session closed হলে
            if(res.status === 'closed'){
                $("#msgInput, #sendBtn, #closeBtn").hide();
                if($("#session-closed-msg").length === 0){
                    $msgBody.after('<div id="session-closed-msg" class="text-center mt-2 text-danger">This chat session has been closed by admin.</div>');
                }
            } else {
                $("#msgInput, #sendBtn, #closeBtn").show();
                $("#session-closed-msg").remove();
            }

            // 🔥 এখানে unread counter update
            let $chatUser = $(`.chat-user[data-id="${activeSession}"]`);

            if(res.count === 0){
                $chatUser.find('.counter').remove();
            } else {
                    $chatUser.find('.counter').remove();
            }
        });
    }


    // Send button click
    $("#sendBtn").on("click", function() {
        sendMessage();
    });

    $("#msgInput").on("keypress", function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault(); // default newline disable
            sendMessage();
        }
    });

    // Send message
    function sendMessage() {
        let text = $("#msgInput").val();
        if (!text || !activeSession) return;
        let url = "{{ route('admin.chat.send') }}";
        $.post(url, {
            _token: "{{ csrf_token() }}",
            chat_session_id: activeSession,
            sender_type: "admin",
            admin_id: "{{ auth()->id() }}",
            message: text
        }, function() {
            $("#msgInput").val('');
            loadMessages();
        });
    }
    // Auto refresh messages
    setInterval(() => {
        if (activeSession) loadMessages();
    }, 3000);

    $("#closeBtn").on("click", function(e) {
        e.preventDefault();

        if (!activeSession) {
            alert("No active session found!");
            return;
        }

        if (confirm("Are you sure to close the chat?")) {
            $.ajax({
                url: "{{ route('admin.chat.close') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    chat_session_id: activeSession
                },
                success: function(res) {
                    if (res.success) {
                        // alert("Chat closed successfully!");

                        // Chat box UI disable
                        $("#msgInput, #sendBtn, #closeBtn").hide();
                        // $("#messageBody").append(`<div id="session-closed-msg" class="text-center mt-2 text-danger">This chat session has been closed by admin.</div>`);
                        loadChatList();
                    } else {
                        alert("Something went wrong!");
                    }
                },
                error: function() {
                    alert("Server error! Please try again.");
                }
            });
        }
    });


</script>
</body>
</html>
