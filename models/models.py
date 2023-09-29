from datetime import datetime

from sqlalchemy import MetaData, Table, Column, Integer, String, TIMESTAMP, ForeignKey, JSON, Boolean

metadata = MetaData()

role = Table(
    "role",
    metadata,
    Column("id", Integer, primary_key=True),
    Column("name", String, nullable=False),
    Column("permissions", JSON),
)

user = Table(
    "user",
    metadata,
    Column("id", Integer, primary_key=True),
    Column("email", String, nullable=False),
    Column("username", String, nullable=False),
    Column("registered_at", TIMESTAMP, default=datetime.utcnow),
    Column("role_id", Integer, ForeignKey(role.c.id)),
    Column("hashed_password", String, nullable=False),
    Column("is_active", Boolean, default=True, nullable=False),
    Column("is_superuser", Boolean, default=False, nullable=False),
    Column("is_verified", Boolean, default=False, nullable=False),
)


announcement = Table(
    "announcement",
    metadata,
    Column("id", Integer, primary_key=True),
    Column("user_id", Integer, ForeignKey(user.c.id)),
    Column("title", String, nullable=False),
    Column("subtitle", String, nullable=True),
    Column("text", String, nullable=False),
    Column("is_hidden", Boolean, default=True, nullable=False),
    Column("registered_at", TIMESTAMP, default=datetime.utcnow),
)


comment = Table(
    "comment",
    metadata,
    Column("id", Integer, primary_key=True),
    Column("announcement_id", Integer, ForeignKey(announcement.c.id)),
    Column("user_id", Integer, ForeignKey(user.c.id)),
    Column("text", String, nullable=False),
    Column("registered_at", TIMESTAMP, default=datetime.utcnow),
)