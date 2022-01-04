<?php

namespace App\Companion;
/**
 * HTTP Kérések standard visszatérési értékük
 *
 * Minden visszatérés dokumentálva.
 *
 * Forrás: https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
 */
class ResponseCodes {
    /**
     * The request succeeded. The result meaning of success depends on the HTTP method:
     *
     * GET: The resource has been fetched and transmitted in the message body.
     *
     * HEAD: The representation headers are included in the response without any message body.
     *
     * PUT or POST: The resource describing the result of the action is transmitted in the message body.
     *
     * TRACE: The message body contains the request message as received by the server.
     */
    const RESPONSE_OK = 200;
    /**
     * The request succeeded = and a new resource was created as a result.
     * This is typically the response sent after POST requests = or some PUT requests.
     */
    const RESPONSE_CREATED = 201;
    /**
     * The request has been received but not yet acted upon.
     * It is noncommittal = since there is no way in HTTP to later send an asynchronous
     * response indicating the outcome of the request. It is intended for cases where
     * another process or server handles the request = or for batch processing.
     */
    const RESPONSE_ACCEPTED = 202;
    /**
     * There is no content to send for this request = but the headers may be useful.
     * The user agent may update its cached headers for this resource with the new ones.
     */
    const RESPONSE_NO_CONTENT = 204;
    /**
     * The URL of the requested resource has been changed permanently.
     * The new URL is given in the response.
     */
    const REDIRECT_MOVED_PERMANENTLY = 301;
    /**
     * This response code means that the URI of requested resource has been changed temporarily.
     * Further changes in the URI might be made in the future.
     * Therefore = this same URI should be used by the client in future requests.
     */
    const REDIRECT_FOUND = 302;
    /**
     * The server sent this response to direct the client to
     * get the requested resource at another URI with a GET request.
     */
    const REDIRECT_SEE_OTHER = 303;
    /**
     * This is used for caching purposes.
     * It tells the client that the response has not been modified,
     * so the client can continue to use the same cached version of the response.
     */
    const REDIRECT_NOT_MODIFIED = 304;
    /**
     * The server sends this response to direct the client to get the requested
     * resource at another URI with same method that was used in the prior request.
     *
     * This has the same semantics as the 302 Found HTTP response code,
     * with the exception that the user agent must not change the HTTP method used:
     * if a POST was used in the first request = a POST must be used in the second request.
     */
    const REDIRECT_TEMPORARY = 307;
    /**
     * This means that the resource is now permanently located at another URI,
     * specified by the Location: HTTP Response header.
     *
     * This has the same semantics as the 301 Moved Permanently HTTP response code,
     * with the exception that the user agent must not change the HTTP method used:
     * if a POST was used in the first request = a POST must be used in the second request.
     */
    const REDIRECT_PERMANENT = 308;
    /**
     * The server could not understand the request due to invalid syntax.
     */
    const ERROR_BAD_REQUEST = 400;
    /**
     * Although the HTTP standard specifies unauthorized,
     * semantically this response means unauthenticated.
     * That is = the client must authenticate itself to get the requested response.
     */
    const ERROR_UNAUTHORIZED = 401;
    /**
     * The client does not have access rights to the content; that is = it is unauthorized,
     *  so the server is refusing to give the requested resource.
     * Unlike 401 Unauthorized = the client's identity is known to the server.
     */
    const ERROR_FORBIDDEN = 403;
    /**
     * The server can not find the requested resource.
     * In an API = this can also mean that the endpoint is valid but the resource itself does not exist.
     * Servers may also send this response instead of 403 Forbidden to hide the existence of
     * a resource from an unauthorized client.
     */
    const ERROR_NOT_FOUND = 404;
    /**
     * The request method is known by the server but is not supported by the target resource.
     * For example = an API may not allow calling DELETE to remove a resource.
     */
    const ERROR_METHOD_NOT_ALLOWED = 405;
    /**
     * This response is sent when the web server = after performing server-driven content negotiation,
     * doesn't find any content that conforms to the criteria given by the user agent
     */
    const ERROR_NOT_ACCEPTABLE = 406;
    /**
     * This response is sent on an idle connection by some servers,
     * even without any previous request by the client.
     * It means that the server would like to shut down this unused connection.
     *
     * This response is used much more since some browsers = like Chrome,
     * Firefox 27+ = or IE9 = use HTTP pre-connection mechanisms to speed up surfing.
     * Also note that some servers merely shut down the connection without sending this message.
     */
    const ERROR_TIMEOUT = 408;
    /**
     * This response is sent when a request conflicts with the current state of the server.
     */
    const ERROR_CONFLICT = 409;
    /**
     * Request entity is larger than limits defined by server.
     * The server might close the connection or return an Retry-After header field.
     */
    const ERROR_PAYLOAD_TOO_LARGE = 413;
    /**
     * The URI requested by the client is longer than the server is willing to interpret.
     */
    const ERROR_URI_TOO_LONG = 414;
    /**
     * The server refuses the attempt to brew coffee with a teapot.
     */
    const ERROR_IM_A_TEAPOT = 418;
    /**
     * The request was well-formed but was unable to be followed due to semantic errors.
     */
    const ERROR_UNROCESSABLE_ENTITY = 422;
    /**
     * The user has sent too many requests in a given amount of time (rate limiting.
     */
    const ERROR_TOO_MANY_REQUESTS = 429;
    /**
     * The user agent requested a resource that cannot legally be provided,
     * such as a web page censored by a government.
     */
    const ERROR_UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    /**
     * The server has encountered a situation it does not know how to handle.
     */
    const ERROR_INTERNAL = 500;
    /**
     * The request method is not supported by the server and cannot be handled.
     * The only methods that servers are required to support (and therefore that must not return this code are GET and HEAD.
     */
    const ERROR_NOT_IMPLEMENTED = 501;
    /**
     * The server is not ready to handle the request.
     * Common causes are a server that is down for maintenance or that is overloaded.
     *
     * Note that together with this response = a user-friendly page explaining the problem should be sent.
     * This response should be used for temporary conditions and the Retry-After HTTP header should,
     * if possible = contain the estimated time before the recovery of the service.
     * The webmaster must also take care about the caching-related headers that are sent
     * along with this response = as these temporary condition responses should usually not be cached.
     */
    const ERROR_SERVICE_UNAVAILABLE = 503;

}
