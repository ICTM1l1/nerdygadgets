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
 * Get all contact requests filed on a specific date.
 *
 * @param DateTime $date
 *   A datetime object of the date of which the contact requests are to be retrieved.
 *
 * @return array
 *   An array of the requests filed on the specified date.
 */
function getContactRequestsByDate(DateTime $date){
    return select(
        "SELECT * FROM contact_requests 
                WHERE ContactRequestDate LIKE :date",
        ["date" => $date->format("Y-m-d") . " %"]
    );
}

/**
 * Get all contact requests filed in the week in which the date specified by $date falls.
 *
 * @param DateTime $date
 *   The date which falls in the week of which we wish to retrieve the contact requests.
 *
 * @return array
 *   Array of all contact requests filed in the specified week.
 * @throws Exception
 */
function getContactRequestsInWeekByDate(DateTime $date){
    $week = get_week_boundaries_from_date($date);
    return select(
        "SELECT * FROM contact_requests
                WHERE DATE(ContactRequestDate) >= :start
                AND DATE(ContactRequestDate) <= :end",
        $week
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