from datetime import datetime

from sqlalchemy import MetaData, Table, Column, Integer, String, TIMESTAMP, ForeignKey, Boolean

from ..auth.models import user

metadata = MetaData()

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