<?php

namespace Defro\Quotable\Enum;

enum SortBy: string
{
    case AUTHOR = 'author';
    case CONTENT = 'content';
    case DATE_ADDED = 'dateAdded';
    case DATE_MODIFIED = 'dateModified';
    case NAME = 'name';
    case QUOTE_COUNT = 'quoteCount';
}
