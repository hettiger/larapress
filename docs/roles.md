# Roles

There are 3 user groups with different permissions per default:

## Administrator

| Permission            |       |
| :-------------------- | :---- |
| access.backend        | True  |
| administrator.edit    | True  |
| owner.add             | True  |
| owner.remove          | True  |
| owner.edit            | True  |
| moderator.add         | True  |
| moderator.remove      | True  |
| moderator.edit        | True  |
| self.remove           | False |
| content.administrate  | True  |
| content.manage        | True  |
| configuration.edit    | True  |
| partial.add           | True  |
| partial.edit          | True  |

## Owner

| Permission            |       |
| :-------------------- | :---- |
| access.backend        | True  |
| administrator.edit    | False |
| owner.add             | False |
| owner.remove          | False |
| owner.edit            | True  |
| moderator.add         | True  |
| moderator.remove      | True  |
| moderator.edit        | True  |
| self.remove           | False |
| content.administrate  | False |
| content.manage        | True  |
| configuration.edit    | True  |
| partial.add           | False |
| partial.edit          | True  |

## Moderator

| Permission            |       |
| :-------------------- | :---- |
| access.backend        | True  |
| administrator.edit    | False |
| owner.add             | False |
| owner.remove          | False |
| owner.edit            | False |
| moderator.add         | False |
| moderator.remove      | False |
| moderator.edit        | False |
| self.remove           | True  |
| content.administrate  | False |
| content.manage        | True  |
| configuration.edit    | False |
| partial.add           | False |
| partial.edit          | False |
