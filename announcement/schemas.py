from pydantic import BaseModel


class AnnouncementCreate(BaseModel):
    id: int
    user_id: int
    title: str
    subtitle: str
    text: str
    is_hidden: bool


class AnnouncementUpdate(BaseModel):
    user_id: int
    title: str
    subtitle: str
    text: str
    is_hidden: bool

class CommentCreate(BaseModel):
    id: int
    announcement_id: int
    user_id: int
    text: str

class CommentUpdate(BaseModel):
    id: int
    announcement_id: int
    user_id: int
    text: str