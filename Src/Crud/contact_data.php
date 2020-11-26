<?php
/**
 * Create a contact request.
 *
 * @param string $name
 *   Name of the person filing the reauest.
 * @param string $email
 *   Email address of the person filing the request.
 * @param string $subject
 *   The subject the contact request pertains to.
 * @param string $message
 *   The message body text.
 *
 * @return int
 *   The contact request ID.
 */
function createContactRequest(string $name, string $email, string $subject, string $message){
    return insert("contact_requests", [
        "ContactRequestName" => $name,
        "ContactRequestEmail" => $email,
        "ContactRequestSubject" => $subject,
        "ContactRequestMessage" => $message
    ]);
}

/**
 * Retrieve contact request by ID.
 *
 * @param int $id
 *   The ID of the contact request that is to be retrieved.
 *
 * @return array
 *   The retrieved contact request.*/
function getContactRequestByID(int $id){
    return selectFirst(
        "SELECT * FROM contact_requests
                WHERE ContactRequestID = :id",
        ["id" => $id]
    );
}


/**
 * Remove contact request based on ID.
 *
 * @param int $id
 *   The ID of the contact request that is to be removed.
 *
 * @return int
 *   The result of the query.*/
function removeContactRequest(int $id){
    return delete("contact_requests", ["ContactRequestID" => $id]);
}