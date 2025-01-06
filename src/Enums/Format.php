<?php

namespace BasilLangevin\LaravelDataSchemas\Enums;

/**
 * This Enum contains all of the valid formats for the `format` keyword.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation.html#rfc.section.7.3
 */
enum Format: string
{
    // 7.3.1. Dates, Times, and Duration
    case DateTime = 'date-time';
    case Date = 'date';
    case Time = 'time';
    case Duration = 'duration';

    // 7.3.2. Email
    case Email = 'email';
    case IdnEmail = 'idn-email';

    // 7.3.3. Hostname
    case Hostname = 'hostname';
    case IdnHostname = 'idn-hostname';

    // 7.3.4. IP Addresses
    case IPv4 = 'ipv4';
    case IPv6 = 'ipv6';

    // 7.3.5. Resource Identifiers
    case Uri = 'uri';
    case UriReference = 'uri-reference';
    case Iri = 'iri';
    case IriReference = 'iri-reference';
    case Uuid = 'uuid';

    // 7.3.6. URI Template
    case UriTemplate = 'uri-template';

    // 7.3.7. JSON Pointers
    case JsonPointer = 'json-pointer';
    case RelativeJsonPointer = 'relative-json-pointer';

    // 7.3.8. RegEx
    case Regex = 'regex';
}
