<?php
session_start();
require 'includes/dbh.inc.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

include 'includes/HTML-head.php';

// Update the status of messages to read for the specific user
if (isset($_GET['id'])) {
    $otherUserId = trim(mysqli_real_escape_string($conn, $_GET['id']));

    $update_sql = "UPDATE messages SET is_read = 1 WHERE user_to = ? AND user_from = ?";
    $update_stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($update_stmt, $update_sql)) {
        mysqli_stmt_bind_param($update_stmt, "ii", $_SESSION['userId'], $otherUserId);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
    }
}
?>

<link href="css/inbox.css" rel="stylesheet">
<style>
    /* Add space below each chat message */
    .outgoing_msg,
    .incoming_msg {
        margin-bottom: 15px; /* Adjust the value as needed */
    }
    .image-preview img {
        max-width: 100px; /* Adjust the maximum width as needed */
        max-height: 100px; /* Adjust the maximum height as needed */
        padding: 10px;
    }
</style>

</head>

<body style="background:#ffff">
    <?php include 'includes/navbar2.php'; ?>
    <br>
    <br>
    <br>

    <div class="cover">
        <div class="messaging">
            <div class="inbox_msg">
                <div class="inbox_people" style="height: 100%; overflow-y: auto">
                    <div class="headind_srch">
                        <div class="recent_heading">
                            <h2>Inbox</h2>
                        </div>
                    </div>
                    <div class="inbox_chat">
                        <?php
$sql = "SELECT c.*, m.user_from, m.message, m.message_timestamp AS last_message_time, m.file_name AS message_file, u.username
FROM conversation c
LEFT JOIN (
    SELECT conversation_id, user_from, message, message_timestamp, file_name
    FROM messages
    WHERE id IN (
        SELECT MAX(id)
        FROM messages
        GROUP BY conversation_id
    )
) m ON c.id = m.conversation_id
LEFT JOIN users u ON m.user_from = u.id
WHERE (c.user_one = ? OR c.user_two = ?)
GROUP BY CASE
    WHEN c.user_one = ? THEN c.user_two
    ELSE c.user_one
END
ORDER BY last_message_time DESC";


$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    die('SQL error');
} else {
    $userId = $_SESSION['userId'];
    mysqli_stmt_bind_param($stmt, "sss", $userId, $userId, $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $otherUserId = $row['user_one'] != $userId ? $row['user_one'] : $row['user_two'];
        $sql_user = "SELECT * FROM users WHERE id = ?";
        $stmt_user = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt_user, $sql_user)) {
            mysqli_stmt_bind_param($stmt_user, "s", $otherUserId);
            mysqli_stmt_execute($stmt_user);
            $result_user = mysqli_stmt_get_result($stmt_user);
            $user = mysqli_fetch_assoc($result_user);
            if ($user) {
                // Fetch the latest message for the conversation
                $latest_message = $row['message'];
                $file_name = $row['message_file']; // Fetch the file name
?>
                <a href='./message.php?id=<?php echo $user['id']; ?>'>
                    <div class="chat_list ">
                        <div class="chat_people">
                            <div class="chat_img">
                                <img class="chat_people_img" src="uploads/<?php echo $user['userImg'] ?>">
                            </div>
                            <div class="chat_ib">
                                <h5>
                                    <?php echo ucwords($user['username']) ?>
                                    <span class="chat_date">Business User</span>
                                </h5>
                                <!-- Display the latest message and file if available -->
                                <?php
                                if ($file_name) {
                                    echo "<p>Sent a picture.</p>";
                                } else {
                                    echo "<p>{$latest_message}</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </a>
<?php
            }
        }
    }
}
?>
                    </div>
                </div>

                <div class="mesgs">
                    <div class="msg_history" style="max-height: 510px; overflow-y: auto;">
                        <?php
                        $conversation_id = ''; // Initialize $conversation_id

                        if (isset($_GET['id'])) {

                            $user_two = trim(mysqli_real_escape_string($conn, $_GET['id']));

                            $sql = "select id from users where id = ? and id != ?";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                die("SQL error");
                            } else {
                                mysqli_stmt_bind_param($stmt, "ss", $user_two, $_SESSION['userId']);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_store_result($stmt);

                                $resultCheck = mysqli_stmt_num_rows($stmt);

                                if ($resultCheck === 0) {
                                    die("Invalid $_GET ID.");
                                } else {
                                    $sql = "select * from conversation where (user_one = ? AND user_two = ?) or (user_one = ? AND user_two = ?)";
                                    $stmt = mysqli_stmt_init($conn);
                                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                                        die("SQL error");
                                    } else {
                                        mysqli_stmt_bind_param($stmt, "ssss", $user_two, $_SESSION['userId'], $_SESSION['userId'], $user_two);

                                        mysqli_stmt_execute($stmt);
                                        $conver = mysqli_stmt_get_result($stmt);
                                        mysqli_stmt_free_result($stmt);

                                        if (mysqli_num_rows($conver) > 0) {

                                            $fetch = mysqli_fetch_assoc($conver);
                                            $conversation_id = $fetch['id'];
                                        } else {
                                            $sql = "insert into conversation(user_one, user_two) values (?,?)";
                                            $stmt = mysqli_stmt_init($conn);

                                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                                die("SQL error");
                                            } else {
                                                mysqli_stmt_bind_param($stmt, "ss", $_SESSION['userId'], $user_two);
                                                mysqli_stmt_execute($stmt);

                                                $conversation_id = mysqli_insert_id($conn);
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            die("<div class='text-center'>
                                        <br><br><br>
                                        <h1 class='text-black'>Click on a person to start chatting</h1>
                                     </div>");
                        }

                        ?>
                    </div>
                    <div class="type_msg">
                        <div class="input_msg_write">
                            <input type="hidden" id="conversation_id" value="<?php echo base64_encode($conversation_id); ?>">
                            <input type="hidden" id="user_form" value="<?php echo base64_encode($_SESSION['userId']); ?>">
                            <input type="hidden" id="user_to" value="<?php echo base64_encode($user_two); ?>">
                            <label for="file" class="file-label">
                                <input type="file" id="file" name="file" accept=".jpg, .jpeg, .png, .gif, .pdf, .doc, .docx">
                                <span class="file-icon"><i class="fas fa-images"></i></span>
                            </label>
                            <div id="imagePreview" class="image-preview"></div>
                            <textarea id="message" class="write_msg form-control-plaintext" placeholder="Type a message"></textarea>
                            <button id="reply" class="msg_send_btn" type="button" onclick="sendMessage();"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                            <button id="removeImageBtn" class="remove-image-btn" onclick="removeImagePreview()" style="display: none;">X</button> <!-- Initially hidden -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script>
        document.getElementById("file").addEventListener("change", function() {
            var file = this.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                var imagePreview = document.getElementById("imagePreview");
                imagePreview.innerHTML = '<img src="' + e.target.result + '" class="preview-image">';
                document.getElementById("removeImageBtn").style.display = "block"; // Show the remove image button
            }

            reader.readAsDataURL(file);
        });

        function removeImagePreview() {
            document.getElementById("file").value = ""; // Clear the file input
            document.getElementById("imagePreview").innerHTML = ""; // Clear the image preview
            document.getElementById("removeImageBtn").style.display = "none"; // Hide the remove image button
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var messageTextarea = document.getElementById("message");

            // Add event listener for keypress event
            messageTextarea.addEventListener("keypress", function(event) {
                // Check if the Enter key is pressed without Shift key
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault(); // Prevent newline in textarea
                    sendMessageFromTextarea(); // Call your sendMessage function here
                }
            });
        });

        function sendMessageFromTextarea() {
            // Implement your sendMessage function logic here
            document.getElementById("reply").click(); // Trigger click event on send button
        }
    </script>

    <script>
        function sendMessage() {
            // Disable the send button to prevent multiple clicks
            document.getElementById("reply").disabled = true;

            // Get the message and other necessary data
            var message = document.getElementById("message").value;
            var conversationId = document.getElementById("conversation_id").value;
            var userFrom = document.getElementById("user_form").value;
            var userTo = document.getElementById("user_to").value;

            // Get the file input element
            var fileInput = document.getElementById("file");
            var file = fileInput.files[0]; // Get the selected file

            // Create a FormData object to send both text and file data
            var formData = new FormData();
            formData.append("message", message);
            formData.append("conversation_id", conversationId);
            formData.append("user_from", userFrom);
            formData.append("user_to", userTo);

            // Check if a file is selected
            if (file) {
                formData.append("file", file); // Append the file to the FormData object
            }

            // Send the message with file data using fetch API
            fetch("send_message.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => {
                    // Handle response
                    // Clear message input and file input after sending
                    document.getElementById("message").value = "";
                    fileInput.value = "";
                    document.getElementById("imagePreview").innerHTML = ""; // Clear image preview

                    // Hide the remove image button after sending
                    document.getElementById("removeImageBtn").style.display = "none";

                    // Enable the send button after successful send
                    document.getElementById("reply").disabled = false;
                })
                .catch(error => {
                    console.error("Error:", error);
                    // Enable the send button if an error occurs
                    document.getElementById("reply").disabled = false;
                });
        }
    </script>

</body>

</html>