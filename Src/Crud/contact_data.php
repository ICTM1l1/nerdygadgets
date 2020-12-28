<?php

/**
 * Create a contact request.
 *
 * @param string $name
 *   Name of the person filing the request.
 * @param string $email
 *   Email address of the person filing the request.
 * @param string $subject
 *   The subject the contact request pertains to.
 * @param string $message
 *   The message body text.
 *
 * @return int
 *   The contact request Id.
 */
function createContactRequest(string $name, string $email, string $subject, string $message) {
    return insert('contact_requests', [
        'ContactRequestName' => $name,
        'ContactRequestEmail' => $email,
        'ContactRequestSubject' => $subject,
        'ContactRequestMessage' => $message
    ]);
}

/**
 * Retrieve contact requests.
 *
 * @return array
 *   The retrieved contact requests.
 */
function getContactRequests() {
    return select('
        SELECT ContactRequestID, ContactRequestName, ContactRequestSubject, ContactRequestMessage,
        DATE(ContactRequestDate) ContactRequestDate, ContactRequestEmail
        FROM contact_requests
        ORDER BY ContactRequestID DESC
    ');
}

/**
 * Get all contact requests filed on a specific date.
 *
 * @param string $date
 *   The date of which the contact requests are to be retrieved.
 *
 * @return array
 *   An array of the requests filed on the specified date.
 */
function getContactRequestsByDate(string $date) {
    return select('
        SELECT ContactRequestID, ContactRequestName, ContactRequestSubject, ContactRequestMessage,
        DATE(ContactRequestDate) ContactRequestDate, ContactRequestEmail
        FROM contact_requests 
        WHERE DATE(ContactRequestDate) = :date
    ', ['date' => $date]);
}

/**
 * Remove contact request based on ID.
 *
 * @param int $id
 *   The ID of the contact request that is to be removed.
 *
 * @return int
 *   The result of the query.
 */
function removeContactRequest(int $id) {
    return delete('contact_requests', ['ContactRequestID' => $id]);
}