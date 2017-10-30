# promoteMember

[Return to available methods list](index.md)

### Description:

Promotes a member in specifies Chat ID. (Obviously, it doesn't work in a private Chat.)

### Parameters:

| Name | Required | Type |
|------|----------|------|
|chat_id|Yes|int OR string|
|user_id|Yes|int|
|can\_change_info|No|boolean|
|can\_delete_messages|No|boolean|
|can\_invite_users|No|boolean|
|can\_restrict_members|No|boolean|
|can\_promote_members|No|boolean|

### Usage:

```php
promoteMember($chat_id, $user_id, $can_change_info, $can_delete_messages, $can_invite_users, $can_restrict_members, $can_promote_members);
```

##### Example:

```php
promoteMember(-123456789, 135792468, true, false, true);
```

Or, if Chat is a supergroup or a channel:

```php
promoteMember("@channelusername", 135792468, true, false, true);
```