from datetime import datetime
from enum import Enum
from typing import List, Optional, Union

from fastapi_users import fastapi_users, FastAPIUsers
from pydantic import BaseModel, Field

from fastapi import FastAPI, Request, status, Depends
from fastapi.encoders import jsonable_encoder
from fastapi.exceptions import RequestValidationError
from fastapi.responses import JSONResponse

from auth.auth import auth_backend
from auth.database import User
from auth.manager import get_user_manager
from auth.schemas import UserRead, UserCreate

from database import engine
from models.models import announcement
from sqlalchemy.orm import sessionmaker
from announcement.schemas import AnnouncementCreate, AnnouncementUpdate, CommentCreate, CommentUpdate

app = FastAPI(
    title="Trading App"
)

fastapi_users = FastAPIUsers[User, int](
    get_user_manager,
    [auth_backend],
)

app.include_router(
    fastapi_users.get_auth_router(auth_backend),
    prefix="/auth/jwt",
    tags=["auth"],
)

app.include_router(
    fastapi_users.get_register_router(UserRead, UserCreate),
    prefix="/auth",
    tags=["auth"],
)

current_user = fastapi_users.current_user()

@app.get("/announcement")
def announcement_list(user: User = Depends(current_user)):
    Session = sessionmaker(bind=engine)
    session = Session()
    return session.query(announcement).all()


@app.post("/announcement/add")
def announcement_add(new_announcement: AnnouncementCreate, user: User = Depends(current_user)):

    query = announcement.insert().values(
        id=new_announcement.id,
        user_id=new_announcement.user_id,
        title=new_announcement.title,
        subtitle=new_announcement.subtitle,
        text=new_announcement.text,
        is_hidden=new_announcement.is_hidden,
    )

    conn = engine.connect()
    result = conn.execute(query)
    return result

@app.post("/announcement/edit/{announcement_id}")
def announcement_edit(announcement_id: int, edit_announcement: AnnouncementUpdate, user: User = Depends(current_user)):

    query = announcement.insert().values(
        id=edit_announcement.id,
        user_id=edit_announcement.user_id,
        title=edit_announcement.title,
        subtitle=edit_announcement.subtitle,
        text=edit_announcement.text,
        is_hidden=edit_announcement.is_hidden,
    )

    conn = engine.connect()
    result = conn.execute(query)
    return result

@app.get("/unprotected-route")
def unprotected_route():
    return f"Hello, anonym"